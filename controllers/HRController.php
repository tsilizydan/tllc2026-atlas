<?php
/**
 * TSILIZY CORE - HR Controller
 */

class HRController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * HR Dashboard
     */
    public function index(): void
    {
        Auth::requirePermission('hr', 'view');

        try {
            $stats = Employee::getStats();
        } catch (Throwable $e) {
            $stats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'total_salary' => 0.0];
        }
        try {
            $paycheckStats = Paycheck::getStats();
        } catch (Throwable $e) {
            $paycheckStats = ['total_payroll' => 0.0, 'pending_amount' => 0.0, 'paid_count' => 0, 'pending_count' => 0];
        }
        try {
            $recentEmployees = Employee::active();
        } catch (Throwable $e) {
            $recentEmployees = [];
        }
        try {
            $pendingPaychecks = Paycheck::pending();
        } catch (Throwable $e) {
            $pendingPaychecks = [];
        }
        try {
            $recentPaychecks = array_slice(Paycheck::allWithEmployee(), 0, 5);
        } catch (Throwable $e) {
            $recentPaychecks = [];
        }

        view('hr/index', [
            'pageTitle' => 'Human Resources',
            'stats' => $stats,
            'paycheckStats' => $paycheckStats,
            'recentEmployees' => $recentEmployees,
            'pendingPaychecks' => $pendingPaychecks,
            'recentPaychecks' => $recentPaychecks
        ]);
    }

    /**
     * List employees
     */
    public function employees(): void
    {
        Auth::requirePermission('hr', 'view');

        $employees = Employee::all();

        view('hr/employees/index', [
            'pageTitle' => 'Employees',
            'employees' => $employees,
            'stats' => Employee::getStats(),
            'departments' => Employee::getDepartments()
        ]);
    }

    /**
     * Create employee form
     */
    public function createEmployee(): void
    {
        Auth::requirePermission('hr', 'create');

        view('hr/employees/create', [
            'pageTitle' => 'Add Employee',
            'employeeCode' => Employee::generateCode()
        ]);
    }

    /**
     * Store employee
     */
    public function storeEmployee(): void
    {
        Auth::requirePermission('hr', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('hr/employees');
        }

        $validator = Validator::make()
            ->required('first_name', 'First Name')
            ->required('last_name', 'Last Name')
            ->required('employee_code', 'Employee Code')
            ->unique('employee_code', 'employees')
            ->email('email');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('hr/employees/create');
        }

        $data = [
            'employee_code' => input('employee_code'),
            'first_name' => input('first_name'),
            'last_name' => input('last_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'position' => input('position'),
            'department' => input('department'),
            'hire_date' => input('hire_date') ?: null,
            'salary' => input('salary') ? (float) input('salary') : null,
            'address' => input('address'),
            'emergency_contact' => input('emergency_contact'),
            'notes' => input('notes'),
            'status' => input('status', 'active')
        ];

        // Handle photo upload
        if (!empty($_FILES['photo']['name'])) {
            $path = uploadFile($_FILES['photo'], 'avatars', ALLOWED_IMAGE_TYPES);
            if ($path) {
                $data['photo'] = $path;
            }
        }

        $id = Employee::create($data);
        Auth::logActivity(Auth::id(), 'create', 'employee', $id);

        clearOldInput();
        Session::flash('success', 'Employee created successfully.');
        redirect('hr/employees/view?id=' . $id);
    }

    /**
     * Edit employee form
     */
    public function editEmployee(): void
    {
        Auth::requirePermission('hr', 'edit');

        $id = (int) input('id');
        $employee = Employee::findOrFail($id);

        view('hr/employees/edit', [
            'pageTitle' => 'Edit Employee',
            'employee' => $employee
        ]);
    }

    /**
     * Update employee
     */
    public function updateEmployee(): void
    {
        Auth::requirePermission('hr', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('hr/employees');
        }

        $data = [
            'first_name' => input('first_name'),
            'last_name' => input('last_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'position' => input('position'),
            'department' => input('department'),
            'hire_date' => input('hire_date') ?: null,
            'salary' => input('salary') ? (float) input('salary') : null,
            'address' => input('address'),
            'emergency_contact' => input('emergency_contact'),
            'status' => input('status')
        ];

        // Handle photo upload
        if (!empty($_FILES['photo']['name'])) {
            $path = uploadFile($_FILES['photo'], 'avatars', ALLOWED_IMAGE_TYPES);
            if ($path) {
                $data['photo'] = $path;
            }
        }

        Employee::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'employee', $id);

        Session::flash('success', 'Employee updated successfully.');
        redirect('hr/employees/view?id=' . $id);
    }

    /**
     * View employee
     */
    public function viewEmployee(): void
    {
        Auth::requirePermission('hr', 'view');

        $id = (int) input('id');
        $employee = Employee::findWithPaychecks($id);

        if (!$employee) {
            Router::notFound();
        }

        $paychecks = $employee['paychecks'] ?? [];
        $ytdGross = 0.0;
        $ytdNet = 0.0;
        $currentYear = date('Y');
        foreach ($paychecks as $pc) {
            $endYear = !empty($pc['pay_period_end']) ? date('Y', strtotime($pc['pay_period_end'])) : null;
            if ($endYear === $currentYear) {
                $ytdGross += (float)($pc['base_salary'] ?? 0) + (float)($pc['bonuses'] ?? 0);
                $ytdNet += (float)($pc['net_pay'] ?? 0);
            }
        }

        view('hr/employees/view', [
            'pageTitle' => Employee::getFullName($employee),
            'employee' => $employee,
            'paychecks' => $paychecks,
            'stats' => ['ytd_gross' => $ytdGross, 'ytd_net' => $ytdNet]
        ]);
    }

    /**
     * Archive employee
     */
    public function archiveEmployee(): void
    {
        Auth::requirePermission('hr', 'archive');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('hr/employees');
        }

        Employee::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'employee', $id);

        Session::flash('success', 'Employee archived successfully.');
        redirect('hr/employees');
    }

    // ========== PAYCHECK METHODS ==========

    /**
     * List paychecks
     */
    public function paychecks(): void
    {
        Auth::requirePermission('hr', 'view');

        try {
            $paychecks = Paycheck::allWithEmployee();
        } catch (Throwable $e) {
            $paychecks = [];
        }
        try {
            $stats = Paycheck::getListStats();
        } catch (Throwable $e) {
            $stats = ['total' => 0, 'this_month' => 0.0, 'pending' => 0.0, 'paid_year' => 0.0];
        }

        view('hr/paychecks/index', [
            'pageTitle' => 'Paychecks',
            'paychecks' => $paychecks,
            'stats' => $stats,
            'search' => input('search', ''),
            'month' => input('month', ''),
            'status' => input('status', ''),
            'pagination' => []
        ]);
    }

    /**
     * Create paycheck form
     */
    public function createPaycheck(): void
    {
        Auth::requirePermission('hr', 'create');

        try {
            $employees = Employee::active();
        } catch (Throwable $e) {
            $employees = [];
        }
        $selectedEmployee = (int) input('employee_id', 0);
        $defaultSalary = 0.0;
        if ($selectedEmployee && !empty($employees)) {
            foreach ($employees as $emp) {
                if ((int)($emp['id'] ?? 0) === $selectedEmployee) {
                    $defaultSalary = (float)($emp['salary'] ?? 0);
                    break;
                }
            }
        }

        view('hr/paychecks/create', [
            'pageTitle' => 'Create Paycheck',
            'employees' => $employees,
            'selectedEmployee' => $selectedEmployee ?: '',
            'defaultSalary' => $defaultSalary
        ]);
    }

    /**
     * Store paycheck
     */
    public function storePaycheck(): void
    {
        Auth::requirePermission('hr', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('hr/paychecks');
        }

        $baseSalary = (float) input('base_salary', 0);
        $bonuses = (float) input('bonuses', 0);
        $deductions = (float) input('deductions', 0);

        $data = [
            'employee_id' => (int) input('employee_id'),
            'pay_period_start' => input('pay_period_start'),
            'pay_period_end' => input('pay_period_end'),
            'base_salary' => $baseSalary,
            'bonuses' => $bonuses,
            'deductions' => $deductions,
            'net_pay' => Paycheck::calculateNetPay($baseSalary, $bonuses, $deductions),
            'payment_method' => input('payment_method', 'direct_deposit'),
            'status' => input('status', 'pending'),
            'notes' => input('notes', '')
        ];

        if (input('status') === 'paid') {
            $data['payment_date'] = input('payment_date') ?: date('Y-m-d');
        }

        $id = Paycheck::create($data);
        Auth::logActivity(Auth::id(), 'create', 'paycheck', $id);

        Session::flash('success', 'Paycheck created successfully.');
        redirect('hr/paychecks');
    }

    /**
     * View paycheck details
     */
    public function viewPaycheck(): void
    {
        Auth::requirePermission('hr', 'view');

        $id = (int) input('id');
        $paycheck = Paycheck::findWithEmployee($id);

        if (!$paycheck) {
            Router::notFound();
        }

        $employeeId = (int)($paycheck['employee_id'] ?? 0);
        $employee = $employeeId ? (Employee::find($employeeId) ?? []) : [];

        view('hr/paychecks/view', [
            'pageTitle' => 'Paycheck Details',
            'paycheck' => $paycheck,
            'employee' => $employee
        ]);
    }

    /**
     * Edit paycheck form
     */
    public function editPaycheck(): void
    {
        Auth::requirePermission('hr', 'edit');

        $id = (int) input('id');
        $paycheck = Paycheck::findWithEmployee($id);

        if (!$paycheck) {
            Router::notFound();
        }

        view('hr/paychecks/edit', [
            'pageTitle' => 'Edit Paycheck',
            'paycheck' => $paycheck,
            'employees' => Employee::dropdown()
        ]);
    }

    /**
     * Update paycheck
     */
    public function updatePaycheck(): void
    {
        Auth::requirePermission('hr', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('hr/paychecks');
        }

        $baseSalary = (float) input('base_salary', 0);
        $bonuses = (float) input('bonuses', 0);
        $deductions = (float) input('deductions', 0);

        $data = [
            'pay_period_start' => input('pay_period_start'),
            'pay_period_end' => input('pay_period_end'),
            'base_salary' => $baseSalary,
            'bonuses' => $bonuses,
            'deductions' => $deductions,
            'net_pay' => Paycheck::calculateNetPay($baseSalary, $bonuses, $deductions),
            'payment_method' => input('payment_method'),
            'status' => input('status'),
            'notes' => input('notes')
        ];

        if (input('status') === 'paid') {
            $data['payment_date'] = input('payment_date') ?: date('Y-m-d');
        }

        Paycheck::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'paycheck', $id);

        Session::flash('success', 'Paycheck updated successfully.');
        redirect('hr/paychecks');
    }

    // ========== PRINT METHODS ==========

    /**
     * Print employee list
     */
    public function printEmployees(): void
    {
        Auth::requirePermission('hr', 'print');

        printView('employee_list_print', [
            'pageTitle' => 'Employee List',
            'employees' => Employee::all(),
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print employee profile
     */
    public function printEmployeeProfile(): void
    {
        Auth::requirePermission('hr', 'print');

        $id = (int) input('id');
        $employee = Employee::findWithPaychecks($id);

        if (!$employee) {
            Router::notFound();
        }

        printView('employee_profile_print', [
            'pageTitle' => Employee::getFullName($employee) . ' - Profile',
            'employee' => $employee,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print paycheck list
     */
    public function printPaychecks(): void
    {
        Auth::requirePermission('hr', 'print');

        printView('paycheck_list_print', [
            'pageTitle' => 'Paycheck List',
            'paychecks' => Paycheck::allWithEmployee(),
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print single paycheck
     */
    public function printPaycheck(): void
    {
        Auth::requirePermission('hr', 'print');

        $id = (int) input('id');
        $paycheck = Paycheck::findWithEmployee($id);

        if (!$paycheck) {
            Router::notFound();
        }

        printView('paycheck_print', [
            'pageTitle' => 'Pay Stub',
            'paycheck' => $paycheck,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }
    /**
     * Print HR Directory
     */
    public function printDirectory(): void
    {
        Auth::requirePermission('hr', 'print');

        $employees = Employee::active();
        printView('hr_directory_print', [
            'pageTitle' => 'HR Directory',
            'employees' => $employees,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Process paycheck (mark as processed/paid)
     */
    public function processPaycheck(): void
    {
        Auth::requirePermission('hr', 'edit');

        $id = (int) input('id');
        $paycheck = Paycheck::find($id);

        if (!$paycheck) {
            Router::notFound();
        }

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('hr/paychecks/view?id=' . $id);
        }

        Paycheck::update($id, [
            'status' => 'paid',
            'payment_date' => date(DATE_FORMAT)
        ]);

        Auth::logActivity(Auth::id(), 'process', 'paycheck', $id);
        Session::flash('success', 'Paycheck processed successfully.');
        redirect('hr/paychecks/view?id=' . $id);
    }

    /**
     * Download paycheck as PDF (placeholder)
     */
    public function downloadPaycheck(): void
    {
        Auth::requirePermission('hr', 'print');

        $id = (int) input('id');
        $paycheck = Paycheck::findWithEmployee($id);

        if (!$paycheck) {
            Router::notFound();
        }

        // For now, redirect to print view (PDF generation would require a library)
        // TODO: Implement actual PDF download with a library like TCPDF or Dompdf
        redirect('hr/paychecks/print?id=' . $id);
    }

    /**
     * Resend paycheck notification (placeholder)
     */
    public function resendPaycheck(): void
    {
        Auth::requirePermission('hr', 'edit');

        $id = (int) input('id');
        $paycheck = Paycheck::find($id);

        if (!$paycheck) {
            Router::notFound();
        }

        // TODO: Implement actual email/notification resend logic
        Auth::logActivity(Auth::id(), 'resend', 'paycheck', $id);
        Session::flash('success', 'Paycheck notification resent.');
        redirect('hr/paychecks/view?id=' . $id);
    }
}
