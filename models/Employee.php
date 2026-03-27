<?php
/**
 * TSILIZY CORE - Employee Model
 */

class Employee extends Model
{
    protected static string $table = 'employees';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'employee_code', 'first_name', 'last_name', 'email', 'phone',
        'position', 'department', 'hire_date', 'salary', 'status',
        'address', 'emergency_contact', 'notes'
    ];

    /**
     * Get active employees
     */
    public static function active(): array
    {
        return self::where("status = 'active' AND is_archived = 0", [], 'first_name ASC');
    }

    /**
     * Get employees for dropdown
     */
    public static function dropdown(): array
    {
        $rows = Database::fetchAll(
            "SELECT id, CONCAT(first_name, ' ', last_name) as name, employee_code 
             FROM employees 
             WHERE is_archived = 0 
             ORDER BY first_name ASC"
        );
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['name'];
        }
        return $result;
    }

    /**
     * Generate employee code
     */
    public static function generateCode(): string
    {
        return generateEmployeeCode();
    }

    /**
     * Get employee with paychecks
     */
    public static function findWithPaychecks(int $id): ?array
    {
        $employee = self::find($id);
        
        if ($employee) {
            $employee['paychecks'] = Paycheck::where('employee_id = ?', [$id], 'pay_period_end DESC');
        }
        
        return $employee;
    }

    /**
     * Get employee full name
     */
    public static function getFullName(array $employee): string
    {
        return trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?: 'Employee';
    }

    /**
     * Get employees by department
     */
    public static function byDepartment(string $department): array
    {
        return self::where("department = ? AND is_archived = 0", [$department], 'first_name ASC');
    }

    /**
     * Get all departments
     */
    public static function getDepartments(): array
    {
        return self::distinct('department');
    }

    /**
     * Get employee statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'active' => self::count("status = 'active'"),
            'inactive' => self::count("status = 'inactive'"),
            'total_salary' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(salary), 0) FROM employees WHERE status = 'active' AND is_archived = 0"
            )
        ];
    }

    /**
     * Search employees
     */
    public static function searchEmployees(string $term): array
    {
        return self::search(['first_name', 'last_name', 'email', 'employee_code', 'position'], $term);
    }
}
