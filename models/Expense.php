<?php
/**
 * TSILIZY CORE - Expense Model
 */

class Expense extends Model
{
    protected static string $table = 'expenses';
    protected static array $fillable = [
        'category', 'description', 'amount', 'date',
        'vendor', 'payment_method_id', 'receipt_path', 'notes'
    ];

    /**
     * Get expenses with payment method
     */
    public static function allWithPaymentMethod(): array
    {
        return Database::fetchAll(
            "SELECT e.*, pm.name as payment_method 
             FROM expenses e 
             LEFT JOIN payment_methods pm ON e.payment_method_id = pm.id 
             ORDER BY e.date DESC, e.created_at DESC"
        );
    }

    /**
     * Get expense by date range
     */
    public static function byDateRange(string $startDate, string $endDate): array
    {
        return Database::fetchAll(
            "SELECT e.*, pm.name as payment_method 
             FROM expenses e 
             LEFT JOIN payment_methods pm ON e.payment_method_id = pm.id 
             WHERE e.date BETWEEN ? AND ? 
             ORDER BY e.date DESC",
            [$startDate, $endDate]
        );
    }

    /**
     * Get total expenses
     */
    public static function getTotal(?string $startDate = null, ?string $endDate = null): float
    {
        $sql = "SELECT COALESCE(SUM(amount), 0) FROM expenses";
        $params = [];
        
        if ($startDate && $endDate) {
            $sql .= " WHERE date BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        return (float) Database::fetchColumn($sql, $params);
    }

    /**
     * Get expenses by category
     */
    public static function byCategory(?string $startDate = null, ?string $endDate = null): array
    {
        $sql = "SELECT category, SUM(amount) as total, COUNT(*) as count FROM expenses";
        $params = [];
        
        if ($startDate && $endDate) {
            $sql .= " WHERE date BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        $sql .= " GROUP BY category ORDER BY total DESC";
        
        return Database::fetchAll($sql, $params);
    }

    /**
     * Get monthly expenses
     */
    public static function getMonthly(int $year): array
    {
        return Database::fetchAll(
            "SELECT 
                MONTH(date) as month,
                SUM(amount) as total
             FROM expenses 
             WHERE YEAR(date) = ?
             GROUP BY MONTH(date)
             ORDER BY month ASC",
            [$year]
        );
    }

    /**
     * Get expense categories
     */
    public static function getCategories(): array
    {
        return self::distinct('category');
    }

    /**
     * Get top vendors
     */
    public static function topVendors(int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT vendor, SUM(amount) as total, COUNT(*) as count 
             FROM expenses 
             WHERE vendor IS NOT NULL AND vendor != ''
             GROUP BY vendor 
             ORDER BY total DESC 
             LIMIT ?",
            [$limit]
        );
    }
}
