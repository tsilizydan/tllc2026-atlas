<?php
/**
 * TSILIZY CORE - Client Model
 */

class Client extends Model
{
    protected static string $table = 'clients';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'company_name', 'contact_name', 'email', 'phone',
        'address', 'city', 'country', 'website', 'tax_id',
        'notes', 'status'
    ];

    /**
     * Get active clients
     */
    public static function active(): array
    {
        return self::where("status = 'active' AND is_archived = 0", [], 'company_name ASC');
    }

    /**
     * Get client with related data
     */
    public static function findWithRelations(int $id): ?array
    {
        $client = self::find($id);
        
        if ($client) {
            $client['projects'] = Project::where('client_id = ?', [$id], 'created_at DESC');
            $client['invoices'] = Invoice::where('client_id = ?', [$id], 'created_at DESC');
            $client['contracts'] = Contract::where('client_id = ?', [$id], 'created_at DESC');
        }
        
        return $client;
    }

    /**
     * Get clients for dropdown
     */
    public static function dropdown(): array
    {
        $rows = Database::fetchAll(
            "SELECT id, company_name FROM clients WHERE is_archived = 0 ORDER BY company_name ASC"
        );
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['company_name'];
        }
        return $result;
    }

    /**
     * Search clients
     */
    public static function searchClients(string $term): array
    {
        return self::search(['company_name', 'contact_name', 'email', 'phone'], $term);
    }

    /**
     * Get client statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'active' => self::count("status = 'active'"),
            'inactive' => self::count("status = 'inactive'")
        ];
    }

    /**
     * Get total revenue from client
     */
    public static function getTotalRevenue(int $clientId): float
    {
        $result = Database::fetch(
            "SELECT COALESCE(SUM(total), 0) as revenue FROM invoices WHERE client_id = ? AND status = 'paid'",
            [$clientId]
        );
        return (float) ($result['revenue'] ?? 0);
    }
}
