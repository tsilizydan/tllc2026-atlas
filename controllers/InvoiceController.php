<?php
/**
 * TSILIZY CORE - Invoice Controller
 */

class InvoiceController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * List all invoices
     */
    public function index(): void
    {
        Auth::requirePermission('invoices', 'view');

        // Mark overdue invoices
        Invoice::markOverdue();

        $page = (int) input('page', 1);
        $status = input('status', '');

        $where = 'is_archived = 0';
        $params = [];

        if ($status) {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $result = Invoice::paginate($page, ITEMS_PER_PAGE, $where, $params);

        // Get invoices with client info
        $invoices = Invoice::allWithClient();

        view('invoices/index', [
            'pageTitle' => 'Invoices',
            'invoices' => $invoices,
            'stats' => Invoice::getStats(),
            'currentStatus' => $status
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        Auth::requirePermission('invoices', 'create');

        view('invoices/create', [
            'pageTitle' => 'Create Invoice',
            'invoiceNumber' => Invoice::generateNumber(),
            'clients' => Client::dropdown(),
            'projects' => Project::dropdown()
        ]);
    }

    /**
     * Store new invoice
     */
    public function store(): void
    {
        Auth::requirePermission('invoices', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('invoices');
        }

        $validator = Validator::make()
            ->required('invoice_number', 'Invoice Number')
            ->required('client_id', 'Client')
            ->required('issue_date', 'Issue Date')
            ->required('due_date', 'Due Date')
            ->date('issue_date')
            ->date('due_date');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('invoices/create');
        }

        $issueDate = input('issue_date');
        $dueDate = input('due_date');
        if (empty($issueDate) || empty($dueDate) || strtotime($issueDate) === false || strtotime($dueDate) === false) {
            storeOldInput();
            Session::flash('error', 'Valid issue date and due date are required.');
            redirect('invoices/create');
        }

        Database::beginTransaction();

        try {
            $invoiceData = [
                'invoice_number' => input('invoice_number'),
                'client_id' => input('client_id') ?: null,
                'project_id' => input('project_id') ?: null,
                'issue_date' => date('Y-m-d', strtotime($issueDate)),
                'due_date' => date('Y-m-d', strtotime($dueDate)),
                'status' => input('status', 'draft'),
                'tax_rate' => (float) input('tax_rate', 0),
                'discount' => (float) input('discount', 0),
                'currency' => input('currency', 'USD'),
                'notes' => input('notes'),
                'terms' => input('terms'),
                'created_by' => Auth::id()
            ];

            $invoiceId = Invoice::create($invoiceData);

            // Add line items
            $descriptions = input('item_description') ?: [];
            $quantities = input('item_quantity') ?: [];
            $prices = input('item_price') ?: [];

            foreach ($descriptions as $index => $description) {
                if (empty($description)) continue;

                Invoice::addItem($invoiceId, [
                    'description' => $description,
                    'quantity' => (float) ($quantities[$index] ?? 1),
                    'unit_price' => (float) ($prices[$index] ?? 0)
                ]);
            }

            // Recalculate totals
            Invoice::recalculateTotals($invoiceId);

            Auth::logActivity(Auth::id(), 'create', 'invoice', $invoiceId);

            Database::commit();

            clearOldInput();
            Session::flash('success', 'Invoice created successfully.');
            redirect('invoices/view?id=' . $invoiceId);

        } catch (Exception $e) {
            Database::rollback();
            Session::flash('error', 'Failed to create invoice. Please try again.');
            redirect('invoices/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit(): void
    {
        Auth::requirePermission('invoices', 'edit');

        $id = (int) input('id');
        $invoice = Invoice::findWithDetails($id);

        if (!$invoice) {
            Router::notFound();
        }

        view('invoices/edit', [
            'pageTitle' => 'Edit Invoice',
            'invoice' => $invoice,
            'clients' => Client::dropdown(),
            'projects' => Project::dropdown($invoice['client_id'])
        ]);
    }

    /**
     * Update invoice
     */
    public function update(): void
    {
        Auth::requirePermission('invoices', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('invoices');
        }

        $invoice = Invoice::findOrFail($id);

        $issueDate = input('issue_date');
        $dueDate = input('due_date');
        if (empty($issueDate) || empty($dueDate) || strtotime($issueDate) === false || strtotime($dueDate) === false) {
            Session::flash('error', 'Valid issue date and due date are required.');
            redirect('invoices/edit?id=' . $id);
        }

        Database::beginTransaction();

        try {
            $invoiceData = [
                'client_id' => input('client_id') ?: null,
                'project_id' => input('project_id') ?: null,
                'issue_date' => date('Y-m-d', strtotime($issueDate)),
                'due_date' => date('Y-m-d', strtotime($dueDate)),
                'status' => input('status') ?: $invoice['status'],
                'tax_rate' => (float) input('tax_rate', 0),
                'discount' => (float) input('discount', 0),
                'currency' => input('currency', 'USD'),
                'notes' => input('notes'),
                'terms' => input('terms')
            ];

            Invoice::update($id, $invoiceData);

            // Clear and re-add line items
            Invoice::clearItems($id);

            $descriptions = input('item_description') ?: [];
            $quantities = input('item_quantity') ?: [];
            $prices = input('item_price') ?: [];

            foreach ($descriptions as $index => $description) {
                if (empty($description)) continue;

                Invoice::addItem($id, [
                    'description' => $description,
                    'quantity' => (float) ($quantities[$index] ?? 1),
                    'unit_price' => (float) ($prices[$index] ?? 0)
                ]);
            }

            Invoice::recalculateTotals($id);

            Auth::logActivity(Auth::id(), 'update', 'invoice', $id);

            Database::commit();

            Session::flash('success', 'Invoice updated successfully.');
            redirect('invoices/view?id=' . $id);

        } catch (Exception $e) {
            Database::rollback();
            Session::flash('error', 'Failed to update invoice.');
            redirect('invoices/edit?id=' . $id);
        }
    }

    /**
     * View invoice
     */
    public function view(): void
    {
        Auth::requirePermission('invoices', 'view');

        $id = (int) input('id');
        $invoice = Invoice::findWithDetails($id);

        if (!$invoice) {
            Router::notFound();
        }

        view('invoices/view', [
            'pageTitle' => 'Invoice ' . ($invoice['invoice_number'] ?? ''),
            'invoice' => $invoice,
            'company' => CompanyProfile::get() ?? []
        ]);
    }

    /**
     * Archive invoice
     */
    public function archive(): void
    {
        Auth::requirePermission('invoices', 'archive');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('invoices');
        }

        Invoice::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'invoice', $id);

        Session::flash('success', 'Invoice archived successfully.');
        redirect('invoices');
    }

    /**
     * Delete invoice
     */
    public function delete(): void
    {
        Auth::requirePermission('invoices', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('invoices');
        }

        Invoice::delete($id);
        Auth::logActivity(Auth::id(), 'delete', 'invoice', $id);

        Session::flash('success', 'Invoice deleted successfully.');
        redirect('invoices');
    }

    /**
     * Print invoice
     */
    public function print(): void
    {
        Auth::requirePermission('invoices', 'print');

        $id = (int) input('id');
        $invoice = Invoice::findWithDetails($id);

        if (!$invoice) {
            Router::notFound();
        }

        printView('invoice_print', [
            'pageTitle' => 'Invoice ' . ($invoice['invoice_number'] ?? ''),
            'invoice' => $invoice,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print invoice list
     */
    public function printList(): void
    {
        Auth::requirePermission('invoices', 'print');

        $status = input('status', '');
        $invoices = Invoice::allWithClient();
        
        if ($status) {
            $invoices = array_filter($invoices, fn($i) => $i['status'] === $status);
        }

        printView('invoice_list_print', [
            'pageTitle' => 'Invoice List',
            'invoices' => $invoices ?? [],
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT),
            'status' => $status
        ]);
    }
    /**
     * Mark invoice as paid (POST only, with CSRF)
     */
    public function markPaid(): void
    {
        Auth::requirePermission('invoices', 'edit');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('invoices');
        }

        $id = (int) (input('id') ?? 0);
        if ($id <= 0) {
            Session::flash('error', 'Invalid invoice.');
            redirect('invoices');
        }

        $invoice = Invoice::find($id);
        if (!$invoice) {
            Router::notFound();
        }

        Invoice::update($id, ['status' => 'paid']);
        Auth::logActivity(Auth::id(), 'update', 'invoice', $id);

        Session::flash('success', 'Invoice marked as paid.');
        redirect('invoices/view?id=' . $id);
    }

    /**
     * Send invoice (placeholder - implement email/notification logic)
     */
    public function send(): void
    {
        Auth::requirePermission('invoices', 'edit');

        $id = (int) input('id');
        $invoice = Invoice::find($id);

        if (!$invoice) {
            Router::notFound();
        }

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('invoices/view?id=' . $id);
        }

        // Update status to sent if draft
        if ($invoice['status'] === 'draft') {
            Invoice::update($id, ['status' => 'sent']);
        }

        Auth::logActivity(Auth::id(), 'send', 'invoice', $id);

        // TODO: Implement actual email sending logic here
        Session::flash('success', 'Invoice sent successfully.');
        redirect('invoices/view?id=' . $id);
    }
}
