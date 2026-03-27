<?php
/**
 * TSILIZY CORE - Contract Model
 */

class Contract extends Model
{
    protected static string $table = 'contracts';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'contract_number', 'title', 'client_id', 'partner_id', 'type',
        'start_date', 'end_date', 'value', 'status', 'terms',
        'document_path', 'created_by'
    ];

    /**
     * Get contracts with related info
     */
    public static function allWithRelations(bool $includeArchived = false): array
    {
        $where = $includeArchived ? '1=1' : 'c.is_archived = 0';
        
        return Database::fetchAll(
            "SELECT c.*, cl.company_name as client_name, p.company_name as partner_name 
             FROM contracts c 
             LEFT JOIN clients cl ON c.client_id = cl.id 
             LEFT JOIN partners p ON c.partner_id = p.id 
             WHERE {$where} 
             ORDER BY c.created_at DESC"
        );
    }

    /**
     * Find contract with full details
     */
    public static function findWithDetails(int $id): ?array
    {
        return Database::fetch(
            "SELECT c.*, cl.company_name as client_name, cl.contact_name as client_contact,
                    p.company_name as partner_name, p.contact_name as partner_contact
             FROM contracts c 
             LEFT JOIN clients cl ON c.client_id = cl.id 
             LEFT JOIN partners p ON c.partner_id = p.id 
             WHERE c.id = ?",
            [$id]
        );
    }

    /**
     * Generate contract number
     */
    public static function generateNumber(): string
    {
        return generateContractNumber();
    }

    /**
     * Get contracts by type
     */
    public static function byType(string $type): array
    {
        return self::where("type = ? AND is_archived = 0", [$type], 'created_at DESC');
    }

    /**
     * Get active contracts
     */
    public static function active(): array
    {
        return self::where("status = 'active' AND is_archived = 0", [], 'end_date ASC');
    }

    /**
     * Get expiring contracts
     */
    public static function expiringSoon(int $days = 30): array
    {
        return Database::fetchAll(
            "SELECT c.*, cl.company_name as client_name 
             FROM contracts c 
             LEFT JOIN clients cl ON c.client_id = cl.id 
             WHERE c.status = 'active' 
             AND c.end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
             AND c.is_archived = 0 
             ORDER BY c.end_date ASC",
            [$days]
        );
    }

    /**
     * Get contract statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'draft' => self::count("status = 'draft'"),
            'active' => self::count("status = 'active'"),
            'completed' => self::count("status = 'completed'"),
            'terminated' => self::count("status = 'terminated'"),
            'total_value' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(value), 0) FROM contracts WHERE status IN ('active', 'completed') AND is_archived = 0"
            )
        ];
    }

    /**
     * Get contracts for client
     */
    public static function forClient(int $clientId): array
    {
        return self::where("client_id = ? AND is_archived = 0", [$clientId], 'created_at DESC');
    }

    /**
     * Get contracts for partner
     */
    public static function forPartner(int $partnerId): array
    {
        return self::where("partner_id = ? AND is_archived = 0", [$partnerId], 'created_at DESC');
    }
}
