<?php
/**
 * TSILIZY CORE - Finance Controller
 */

class FinanceController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Finance dashboard
     */
    public function index(): void
    {
        Auth::requirePermission('finance', 'view');

        $year = (int) input('year', date('Y'));
        $yearStart = $year . '-01-01';
        $yearEnd = $year . '-12-31';

        try {
            $monthlyIncome = Income::getMonthly($year);
            $monthlyExpenses = Expense::getMonthly($year);
            $chartData = $this->prepareChartData(
                is_array($monthlyIncome) ? $monthlyIncome : [],
                is_array($monthlyExpenses) ? $monthlyExpenses : []
            );
        } catch (Throwable $e) {
            error_log('Finance index chart: ' . $e->getMessage());
            $chartData = ['labels' => [], 'income' => [], 'expenses' => []];
        }

        try {
            $totalIncome = Income::getTotal($yearStart, $yearEnd);
            $totalExpenses = Expense::getTotal($yearStart, $yearEnd);
            $incomeByCategory = Income::byCategory($yearStart, $yearEnd);
            $expenseByCategory = Expense::byCategory($yearStart, $yearEnd);
            $accounts = BankAccount::active();
            $totalBalance = BankAccount::getTotalBalance();
            $recentIncome = Income::byDateRange($yearStart, $yearEnd);
            $recentExpenses = Expense::byDateRange($yearStart, $yearEnd);
            $recentIncome = array_slice($recentIncome, 0, 5);
            $recentExpenses = array_slice($recentExpenses, 0, 5);
        } catch (Throwable $e) {
            error_log('Finance index stats: ' . $e->getMessage());
            $totalIncome = $totalExpenses = $totalBalance = 0.0;
            $incomeByCategory = $expenseByCategory = $accounts = [];
            $recentIncome = $recentExpenses = [];
        }

        view('finance/index', [
            'pageTitle' => 'Finance Dashboard',
            'year' => $year,
            'totalIncome' => $totalIncome ?? 0,
            'totalExpenses' => $totalExpenses ?? 0,
            'incomeByCategory' => $incomeByCategory ?? [],
            'expenseByCategory' => $expenseByCategory ?? [],
            'chartData' => $chartData,
            'accounts' => $accounts ?? [],
            'totalBalance' => $totalBalance ?? 0,
            'recentIncome' => $recentIncome ?? [],
            'recentExpenses' => $recentExpenses ?? []
        ]);
    }

    /**
     * Prepare chart data
     */
    private function prepareChartData(array $income, array $expenses): array
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $incomeData = array_fill(0, 12, 0);
        $expenseData = array_fill(0, 12, 0);

        foreach ($income as $item) {
            $incomeData[$item['month'] - 1] = (float) $item['total'];
        }

        foreach ($expenses as $item) {
            $expenseData[$item['month'] - 1] = (float) $item['total'];
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];
    }

    // ========== INCOME METHODS ==========

    /**
     * List income
     */
    public function income(): void
    {
        Auth::requirePermission('finance', 'view');

        $incomes = Income::allWithRelations();

        view('finance/income/index', [
            'pageTitle' => 'Income',
            'incomes' => $incomes,
            'total' => Income::getTotal(),
            'categories' => Income::getCategories()
        ]);
    }

    /**
     * Create income form
     */
    public function createIncome(): void
    {
        Auth::requirePermission('finance', 'create');

        view('finance/income/create', [
            'pageTitle' => 'Add Income',
            'clients' => Client::dropdown(),
            'paymentMethods' => PaymentMethod::dropdown(),
            'categories' => Income::getCategories()
        ]);
    }

    /**
     * Store income
     */
    public function storeIncome(): void
    {
        Auth::requirePermission('finance', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/income');
        }

        $validator = Validator::make()
            ->required('category', 'Category')
            ->required('amount', 'Amount')
            ->required('date', 'Date')
            ->numeric('amount');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('finance/income/create');
        }

        $data = [
            'category' => input('category'),
            'description' => input('description'),
            'amount' => (float) input('amount'),
            'date' => input('date'),
            'client_id' => input('client_id') ?: null,
            'invoice_id' => input('invoice_id') ?: null,
            'payment_method_id' => input('payment_method_id') ?: null,
            'notes' => input('notes')
        ];

        $id = Income::create($data);
        Auth::logActivity(Auth::id(), 'create', 'income', $id);

        clearOldInput();
        Session::flash('success', 'Income recorded successfully.');
        redirect('finance/income');
    }

    /**
     * Edit income form
     */
    public function editIncome(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');
        $income = Income::findOrFail($id);

        view('finance/income/edit', [
            'pageTitle' => 'Edit Income',
            'income' => $income,
            'clients' => Client::dropdown(),
            'paymentMethods' => PaymentMethod::dropdown(),
            'categories' => Income::getCategories()
        ]);
    }

    /**
     * Update income
     */
    public function updateIncome(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/income');
        }

        $data = [
            'category' => input('category'),
            'description' => input('description'),
            'amount' => (float) input('amount'),
            'date' => input('date'),
            'client_id' => input('client_id') ?: null,
            'payment_method_id' => input('payment_method_id') ?: null,
            'notes' => input('notes')
        ];

        Income::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'income', $id);

        Session::flash('success', 'Income updated successfully.');
        redirect('finance/income');
    }

    /**
     * Delete income
     */
    public function deleteIncome(): void
    {
        Auth::requirePermission('finance', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/income');
        }

        Income::delete($id);
        Auth::logActivity(Auth::id(), 'delete', 'income', $id);

        Session::flash('success', 'Income deleted successfully.');
        redirect('finance/income');
    }

    // ========== EXPENSE METHODS ==========

    /**
     * List expenses
     */
    public function expenses(): void
    {
        Auth::requirePermission('finance', 'view');

        $expenses = Expense::allWithPaymentMethod();

        view('finance/expenses/index', [
            'pageTitle' => 'Expenses',
            'expenses' => $expenses,
            'total' => Expense::getTotal(),
            'categories' => Expense::getCategories()
        ]);
    }

    /**
     * Create expense form
     */
    public function createExpense(): void
    {
        Auth::requirePermission('finance', 'create');

        view('finance/expenses/create', [
            'pageTitle' => 'Add Expense',
            'paymentMethods' => PaymentMethod::dropdown(),
            'categories' => Expense::getCategories()
        ]);
    }

    /**
     * Store expense
     */
    public function storeExpense(): void
    {
        Auth::requirePermission('finance', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/expenses');
        }

        $validator = Validator::make()
            ->required('category', 'Category')
            ->required('amount', 'Amount')
            ->required('date', 'Date')
            ->numeric('amount');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('finance/expenses/create');
        }

        $data = [
            'category' => input('category'),
            'description' => input('description'),
            'amount' => (float) input('amount'),
            'date' => input('date'),
            'vendor' => input('vendor'),
            'payment_method_id' => input('payment_method_id') ?: null,
            'notes' => input('notes')
        ];

        // Handle receipt upload
        if (!empty($_FILES['receipt']['name'])) {
            $path = uploadFile($_FILES['receipt'], 'receipts', array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_DOC_TYPES));
            if ($path) {
                $data['receipt_path'] = $path;
            }
        }

        $id = Expense::create($data);
        Auth::logActivity(Auth::id(), 'create', 'expense', $id);

        clearOldInput();
        Session::flash('success', 'Expense recorded successfully.');
        redirect('finance/expenses');
    }

    /**
     * Edit expense form
     */
    public function editExpense(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');
        $expense = Expense::findOrFail($id);

        view('finance/expenses/edit', [
            'pageTitle' => 'Edit Expense',
            'expense' => $expense,
            'paymentMethods' => PaymentMethod::dropdown(),
            'categories' => Expense::getCategories()
        ]);
    }

    /**
     * Update expense
     */
    public function updateExpense(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/expenses');
        }

        $data = [
            'category' => input('category'),
            'description' => input('description'),
            'amount' => (float) input('amount'),
            'date' => input('date'),
            'vendor' => input('vendor'),
            'payment_method_id' => input('payment_method_id') ?: null,
            'notes' => input('notes')
        ];

        // Handle receipt upload
        if (!empty($_FILES['receipt']['name'])) {
            $path = uploadFile($_FILES['receipt'], 'receipts', array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_DOC_TYPES));
            if ($path) {
                $data['receipt_path'] = $path;
            }
        }

        Expense::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'expense', $id);

        Session::flash('success', 'Expense updated successfully.');
        redirect('finance/expenses');
    }

    /**
     * Delete expense
     */
    public function deleteExpense(): void
    {
        Auth::requirePermission('finance', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/expenses');
        }

        Expense::delete($id);
        Auth::logActivity(Auth::id(), 'delete', 'expense', $id);

        Session::flash('success', 'Expense deleted successfully.');
        redirect('finance/expenses');
    }

    // ========== BANK ACCOUNTS ==========

    /**
     * List bank accounts
     */
    public function accounts(): void
    {
        Auth::requirePermission('finance', 'view');

        view('finance/accounts/index', [
            'pageTitle' => 'Bank Accounts',
            'accounts' => BankAccount::all(),
            'totalBalance' => BankAccount::getTotalBalance()
        ]);
    }

    /**
     * Store bank account
     */
    public function storeAccount(): void
    {
        Auth::requirePermission('finance', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/accounts');
        }

        $data = [
            'bank_name' => input('bank_name'),
            'account_name' => input('account_name'),
            'account_number' => input('account_number'),
            'account_type' => input('account_type'),
            'balance' => (float) input('balance', 0),
            'is_primary' => input('is_primary') ? 1 : 0,
            'is_active' => 1,
            'created_by' => Auth::id(),
            'created_at' => date(DATETIME_FORMAT)
        ];

        BankAccount::create($data);
        Session::flash('success', 'Bank account added successfully.');
        redirect('finance/accounts');
    }

    /**
     * Update bank account
     */
    public function updateAccount(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/accounts');
        }

        $data = [
            'bank_name' => input('bank_name'),
            'account_name' => input('account_name'),
            'account_number' => input('account_number'),
            'account_type' => input('account_type'),
            'balance' => (float) input('balance', 0),
            'is_active' => input('is_active') ? 1 : 0
        ];

        if (input('is_primary')) {
            BankAccount::setPrimary($id);
        }

        BankAccount::update($id, $data);
        Session::flash('success', 'Bank account updated successfully.');
        redirect('finance/accounts');
    }

    /**
     * Delete bank account
     */
    public function deleteAccount(): void
    {
        Auth::requirePermission('finance', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/accounts');
        }

        BankAccount::delete($id);
        Session::flash('success', 'Bank account deleted successfully.');
        redirect('finance/accounts');
    }

    // ========== PAYMENT METHODS ==========

    /**
     * List payment methods
     */
    public function paymentMethods(): void
    {
        Auth::requirePermission('finance', 'view');

        view('finance/payment-methods/index', [
            'pageTitle' => 'Payment Methods',
            'methods' => PaymentMethod::all()
        ]);
    }

    /**
     * Store payment method
     */
    public function storePaymentMethod(): void
    {
        Auth::requirePermission('finance', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/payment-methods');
        }

        $data = [
            'name' => input('name'),
            'type' => input('type', 'other'),
            'details' => input('details'),
            'is_active' => 1
        ];

        PaymentMethod::create($data);
        Session::flash('success', 'Payment method added successfully.');
        redirect('finance/payment-methods');
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/payment-methods');
        }

        $data = [
            'name' => input('name'),
            'type' => input('type'),
            'details' => input('details'),
            'is_active' => input('is_active') ? 1 : 0
        ];

        PaymentMethod::update($id, $data);
        Session::flash('success', 'Payment method updated successfully.');
        redirect('finance/payment-methods');
    }

    /**
     * Delete payment method
     */
    public function deletePaymentMethod(): void
    {
        Auth::requirePermission('finance', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/payment-methods');
        }

        PaymentMethod::delete($id);
        Session::flash('success', 'Payment method deleted successfully.');
        redirect('finance/payment-methods');
    }

    /**
     * Toggle payment method active status
     */
    public function togglePaymentMethod(): void
    {
        Auth::requirePermission('finance', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('finance/payment-methods');
        }

        $method = PaymentMethod::find($id);
        if ($method) {
            PaymentMethod::update($id, [
                'is_active' => $method['is_active'] ? 0 : 1
            ]);
            Session::flash('success', 'Payment method status updated.');
        }

        redirect('finance/payment-methods');
    }

    // ========== REPORTS ==========

    /**
     * Financial reports
     */
    public function reports(): void
    {
        Auth::requirePermission('finance', 'reports');

        $startDate = input('start_date', date('Y-m-01'));
        $endDate = input('end_date', date('Y-m-t'));

        view('finance/reports/index', [
            'pageTitle' => 'Financial Reports',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalIncome' => Income::getTotal($startDate, $endDate),
            'totalExpenses' => Expense::getTotal($startDate, $endDate),
            'incomeByCategory' => Income::byCategory($startDate, $endDate),
            'expenseByCategory' => Expense::byCategory($startDate, $endDate),
            'topVendors' => Expense::topVendors(10)
        ]);
    }

    /**
     * Print financial report
     */
    public function printReport(): void
    {
        Auth::requirePermission('finance', 'print');

        $startDate = input('start_date', date('Y-m-01'));
        $endDate = input('end_date', date('Y-m-t'));

        try {
            $totalIncome = Income::getTotal($startDate, $endDate);
            $totalExpenses = Expense::getTotal($startDate, $endDate);
            $incomeByCategory = Income::byCategory($startDate, $endDate);
            $expenseByCategory = Expense::byCategory($startDate, $endDate);
        } catch (Throwable $e) {
            $totalIncome = 0;
            $totalExpenses = 0;
            $incomeByCategory = [];
            $expenseByCategory = [];
        }
        printView('finance_summary_print', [
            'pageTitle' => 'Financial Summary',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }
    /**
     * Export reports to CSV
     */
    public function exportReports(): void
    {
        Auth::requirePermission('finance', 'reports');

        $startDate = input('start_date', date('Y-m-01'));
        $endDate = input('end_date', date('Y-m-t'));

        $income = Income::byCategory($startDate, $endDate);
        $expenses = Expense::byCategory($startDate, $endDate);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="financial_report_' . $startDate . '_to_' . $endDate . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Income Section
        fputcsv($output, ['INCOME REPORT', $startDate . ' to ' . $endDate]);
        fputcsv($output, ['Category', 'Amount']);
        
        $totalIncome = 0;
        foreach ($income as $item) {
            fputcsv($output, [$item['category'], $item['total']]);
            $totalIncome += $item['total'];
        }
        fputcsv($output, ['TOTAL INCOME', $totalIncome]);
        fputcsv($output, []); // Empty line
        
        // Expense Section
        fputcsv($output, ['EXPENSE REPORT', $startDate . ' to ' . $endDate]);
        fputcsv($output, ['Category', 'Amount']);
        
        $totalExpenses = 0;
        foreach ($expenses as $item) {
            fputcsv($output, [$item['category'], $item['total']]);
            $totalExpenses += $item['total'];
        }
        fputcsv($output, ['TOTAL EXPENSES', $totalExpenses]);
        
        // Net
        fputcsv($output, []);
        fputcsv($output, ['NET PROFIT', $totalIncome - $totalExpenses]);
        
        fclose($output);
        exit;
    }
}
