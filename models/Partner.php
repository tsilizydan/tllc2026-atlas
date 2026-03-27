<?php
/**
 * TSILIZY CORE - Partner Model
 */

class Partner extends Model
{
    protected static string $table = 'partners';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'company_name', 'contact_name', 'email', 'phone',
        'address', 'website', 'partnership_type', 'notes', 'status'
    ];

    /**
     * Get active partners
     */
    public static function active(): array
    {
        return self::where("status = 'active' AND is_archived = 0", [], 'company_name ASC');
    }

    /**
     * Get partners for dropdown
     */
    public static function dropdown(): array
    {
        $rows = Database::fetchAll(
            "SELECT id, company_name FROM partners WHERE is_archived = 0 ORDER BY company_name ASC"
        );
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['company_name'];
        }
        return $result;
    }

    /**
     * Get partner with contracts
     */
    public static function findWithContracts(int $id): ?array
    {
        $partner = self::find($id);
        
        if ($partner) {
            $partner['contracts'] = Contract::forPartner($id);
        }
        
        return $partner;
    }

    /**
     * Get partner statistics
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
     * Get all partnership types
     */
    public static function getPartnershipTypes(): array
    {
        return self::distinct('partnership_type');
    }

    /**
     * Search partners
     */
    public static function searchPartners(string $term): array
    {
        return self::search(['company_name', 'contact_name', 'email'], $term);
    }
}
