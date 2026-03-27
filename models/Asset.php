<?php
/**
 * TSILIZY CORE - Asset Model
 */

class Asset extends Model
{
    protected static string $table = 'assets';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'asset_tag', 'name', 'category_id', 'description', 'serial_number',
        'purchase_date', 'purchase_price', 'warranty_expiry', 'location',
        'status', 'employee_id', 'assigned_at', 'notes'
    ];

    /**
     * Get assets with category and employee info
     */
    public static function allWithRelations(bool $includeArchived = false): array
    {
        $where = $includeArchived ? '1=1' : 'a.is_archived = 0';
        return Database::fetchAll(
            "SELECT a.*, ac.name as category_name, ac.icon as category_icon,
                    e.first_name as employee_first_name, e.last_name as employee_last_name,
                    CONCAT(e.first_name, ' ', e.last_name) as employee_name
             FROM assets a
             LEFT JOIN asset_categories ac ON a.category_id = ac.id
             LEFT JOIN employees e ON a.employee_id = e.id
             WHERE {$where}
             ORDER BY a.created_at DESC"
        );
    }

    /**
     * Find asset with full details
     */
    public static function findWithDetails(int $id): ?array
    {
        return Database::fetch(
            "SELECT a.*, ac.name as category_name, ac.icon as category_icon,
                    e.first_name as employee_first_name, e.last_name as employee_last_name,
                    e.email as employee_email, e.phone as employee_phone, e.position as employee_position,
                    CONCAT(e.first_name, ' ', e.last_name) as employee_name
             FROM assets a
             LEFT JOIN asset_categories ac ON a.category_id = ac.id
             LEFT JOIN employees e ON a.employee_id = e.id
             WHERE a.id = ?",
            [$id]
        );
    }

    /**
     * Generate asset tag
     */
    public static function generateTag(): string
    {
        $prefix = 'AST-' . date('Y');
        $last = Database::fetchColumn(
            "SELECT asset_tag FROM assets WHERE asset_tag LIKE ? ORDER BY id DESC LIMIT 1",
            [$prefix . '%']
        );
        if ($last) {
            $num = (int) substr($last, -4);
            return $prefix . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        }
        return $prefix . '0001';
    }

    /**
     * Get asset statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'available' => self::count("status = 'available'"),
            'assigned' => self::count("status = 'assigned'"),
            'in_repair' => self::count("status = 'in_repair'"),
            'retired' => self::count("status = 'retired'"),
            'lost' => self::count("status = 'lost'"),
            'total_value' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(purchase_price), 0) FROM assets WHERE status IN ('available', 'assigned') AND is_archived = 0"
            ),
            'categories' => (int) Database::fetchColumn("SELECT COUNT(*) FROM asset_categories")
        ];
    }

    /**
     * Assign asset to employee
     */
    public static function assign(int $assetId, ?int $employeeId): bool
    {
        $asset = self::find($assetId);
        if (!$asset) return false;

        $status = $employeeId ? 'assigned' : 'available';
        $data = [
            'employee_id' => $employeeId,
            'status' => $status,
            'assigned_at' => $employeeId ? date(DATETIME_FORMAT) : null
        ];
        return self::update($assetId, $data);
    }

    /**
     * Get assets assigned to employee
     */
    public static function forEmployee(int $employeeId): array
    {
        return self::where("employee_id = ? AND is_archived = 0", [$employeeId], 'assigned_at DESC');
    }

    /**
     * Get assets by category
     */
    public static function byCategory(int $categoryId): array
    {
        return self::where("category_id = ? AND is_archived = 0", [$categoryId], 'name ASC');
    }
}
