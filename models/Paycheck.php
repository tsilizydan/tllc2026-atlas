<?php
/**
 * TSILIZY CORE - Paycheck Model
 */

class Paycheck extends Model
{
    protected static string $table = 'paychecks';
    protected static array $fillable = [
        'employee_id', 'pay_period_start', 'pay_period_end',
        'base_salary', 'bonuses', 'deductions', 'net_pay',
        'payment_date', 'payment_method', 'status', 'notes'
    ];

    /**
     * Get paychecks with employee info
     */
    public static function allWithEmployee(): array
    {
        return Database::fetchAll(
            "SELECT p.*, CONCAT(e.first_name, ' ', e.last_name) as employee_name, e.employee_code 
             FROM paychecks p 
             LEFT JOIN employees e ON p.employee_id = e.id 
             ORDER BY p.pay_period_end DESC"
        );
    }

    /**
     * Get paycheck with employee details
     */
    public static function findWithEmployee(int $id): ?array
    {
        return Database::fetch(
            "SELECT p.*, e.first_name, e.last_name, e.employee_code, e.position, e.department 
             FROM paychecks p 
             LEFT JOIN employees e ON p.employee_id = e.id 
             WHERE p.id = ?",
            [$id]
        );
    }

    /**
     * Get paychecks for employee
     */
    public static function forEmployee(int $employeeId): array
    {
        return self::where('employee_id = ?', [$employeeId], 'pay_period_end DESC');
    }

    /**
     * Calculate net pay
     */
    public static function calculateNetPay(float $baseSalary, float $bonuses = 0, float $deductions = 0): float
    {
        return $baseSalary + $bonuses - $deductions;
    }

    /**
     * Mark paycheck as paid
     */
    public static function markPaid(int $id, ?string $paymentDate = null): bool
    {
        return self::update($id, [
            'status' => 'paid',
            'payment_date' => $paymentDate ?? date('Y-m-d')
        ]);
    }

    /**
     * Get pending paychecks
     */
    public static function pending(): array
    {
        return Database::fetchAll(
            "SELECT p.*, CONCAT(e.first_name, ' ', e.last_name) as employee_name 
             FROM paychecks p 
             LEFT JOIN employees e ON p.employee_id = e.id 
             WHERE p.status = 'pending' 
             ORDER BY p.pay_period_end DESC"
        );
    }

    /**
     * Get payroll statistics
     */
    public static function getStats(?string $month = null): array
    {
        $monthCondition = '';
        $params = [];
        
        if ($month) {
            $monthCondition = "AND DATE_FORMAT(pay_period_end, '%Y-%m') = ?";
            $params[] = $month;
        }
        
        return [
            'total_payroll' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(net_pay), 0) FROM paychecks WHERE status = 'paid' {$monthCondition}",
                $params
            ),
            'pending_amount' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(net_pay), 0) FROM paychecks WHERE status = 'pending'",
                []
            ),
            'paid_count' => (int) Database::fetchColumn(
                "SELECT COUNT(*) FROM paychecks WHERE status = 'paid' {$monthCondition}",
                $params
            ),
            'pending_count' => (int) Database::fetchColumn(
                "SELECT COUNT(*) FROM paychecks WHERE status = 'pending'",
                []
            )
        ];
    }

    /**
     * Get stats for paychecks list view (total count, this month, pending amount, paid this year)
     */
    public static function getListStats(): array
    {
        $thisMonth = date('Y-m');
        $thisYear = date('Y');
        return [
            'total' => (int) Database::fetchColumn("SELECT COUNT(*) FROM paychecks", []),
            'this_month' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(net_pay), 0) FROM paychecks WHERE status = 'paid' AND DATE_FORMAT(pay_period_end, '%Y-%m') = ?",
                [$thisMonth]
            ),
            'pending' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(net_pay), 0) FROM paychecks WHERE status = 'pending'",
                []
            ),
            'paid_year' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(net_pay), 0) FROM paychecks WHERE status = 'paid' AND YEAR(pay_period_end) = ?",
                [$thisYear]
            )
        ];
    }

    /**
     * Get monthly payroll summary
     */
    public static function getMonthlySummary(int $year): array
    {
        return Database::fetchAll(
            "SELECT 
                DATE_FORMAT(pay_period_end, '%Y-%m') as month,
                COUNT(*) as count,
                SUM(net_pay) as total
             FROM paychecks 
             WHERE YEAR(pay_period_end) = ? AND status = 'paid'
             GROUP BY DATE_FORMAT(pay_period_end, '%Y-%m')
             ORDER BY month ASC",
            [$year]
        );
    }
}
