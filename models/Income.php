<?php
/**
 * TSILIZY CORE - Income Model
 */

class Income extends Model
{
    protected static string $table = 'incomes';
    protected static array $fillable = [
        'category', 'description', 'amount', 'date',
        'client_id', 'invoice_id', 'payment_method_id', 'notes'
    ];

    /**
     * Get incomes with relations
     */
    public static function allWithRelations(): array
    {
        return Database::fetchAll(
            "SELECT i.*, c.company_name as client_name, pm.name as payment_method 
             FROM incomes i 
             LEFT JOIN clients c ON i.client_id = c.id 
             LEFT JOIN payment_methods pm ON i.payment_method_id = pm.id 
             ORDER BY i.date DESC, i.created_at DESC"
        );
    }

    /**
     * Get income by date range
     */
    public static function byDateRange(string $startDate, string $endDate): array
    {
        return Database::fetchAll(
            "SELECT i.*, c.company_name as client_name 
             FROM incomes i 
             LEFT JOIN clients c ON i.client_id = c.id 
             WHERE i.date BETWEEN ? AND ? 
             ORDER BY i.date DESC",
            [$startDate, $endDate]
        );
    }

    /**
     * Get total income
     */
    public static function getTotal(?string $startDate = null, ?string $endDate = null): float
    {
        $sql = "SELECT COALESCE(SUM(amount), 0) FROM incomes";
        $params = [];
        
        if ($startDate && $endDate) {
            $sql .= " WHERE date BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        return (float) Database::fetchColumn($sql, $params);
    }

    /**
     * Get income by category
     */
    public static function byCategory(?string $startDate = null, ?string $endDate = null): array
    {
        $sql = "SELECT category, SUM(amount) as total, COUNT(*) as count FROM incomes";
        $params = [];
        
        if ($startDate && $endDate) {
            $sql .= " WHERE date BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $sql .= " GROUP BY category ORDER BY total DESC";
        
        return Database::fetchAll($sql, $params);
    }

    /**
     * Get monthly income
     */
    public static function getMonthly(int $year): array
    {
        return Database::fetchAll(
            "SELECT 
                MONTH(date) as month,
                SUM(amount) as total
             FROM incomes 
             WHERE YEAR(date) = ?
             GROUP BY MONTH(date)
             ORDER BY month ASC",
            [$year]
        );
    }

    /**
     * Get income categories
     */
    public static function getCategories(): array
    {
        return self::distinct('category');
    }
}
