<?php
/**
 * TSILIZY CORE - Contract Controller
 */

class ContractController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * List all contracts
     */
    public function index(): void
    {
        Auth::requirePermission('contracts', 'view');

        $contracts = Contract::allWithRelations();

        view('contracts/index', [
            'pageTitle' => 'Contracts',
            'contracts' => $contracts,
            'stats' => Contract::getStats(),
            'expiringContracts' => Contract::expiringSoon(30)
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        Auth::requirePermission('contracts', 'create');

        view('contracts/create', [
            'pageTitle' => 'Create Contract',
            'contractNumber' => Contract::generateNumber(),
            'clients' => Client::dropdown(),
            'partners' => Partner::dropdown()
        ]);
    }

    /**
     * Store new contract
     */
    public function store(): void
    {
        Auth::requirePermission('contracts', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('contracts');
        }

        $validator = Validator::make()
            ->required('contract_number', 'Contract Number')
            ->required('title', 'Title')
            ->unique('contract_number', 'contracts');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('contracts/create');
        }

        $data = [
            'contract_number' => input('contract_number'),
            'title' => input('title'),
            'client_id' => input('client_id') ?: null,
            'partner_id' => input('partner_id') ?: null,
            'type' => input('type', 'service'),
            'start_date' => input('start_date') ?: null,
            'end_date' => input('end_date') ?: null,
            'value' => input('value') ? (float) input('value') : null,
            'status' => input('status', 'draft'),
            'terms' => input('terms'),
            'created_by' => Auth::id()
        ];

        // Handle document upload
        if (!empty($_FILES['document']['name'])) {
            $path = uploadFile($_FILES['document'], 'contracts', ALLOWED_DOC_TYPES);
            if ($path) {
                $data['document_path'] = $path;
            }
        }

        $id = Contract::create($data);
        Auth::logActivity(Auth::id(), 'create', 'contract', $id);

        clearOldInput();
        Session::flash('success', 'Contract created successfully.');
        redirect('contracts/view?id=' . $id);
    }

    /**
     * Show edit form
     */
    public function edit(): void
    {
        Auth::requirePermission('contracts', 'edit');

        $id = (int) input('id');
        $contract = Contract::findOrFail($id);

        view('contracts/edit', [
            'pageTitle' => 'Edit Contract',
            'contract' => $contract,
            'clients' => Client::dropdown(),
            'partners' => Partner::dropdown()
        ]);
    }

    /**
     * Update contract
     */
    public function update(): void
    {
        Auth::requirePermission('contracts', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('contracts');
        }

        $data = [
            'title' => input('title'),
            'client_id' => input('client_id') ?: null,
            'partner_id' => input('partner_id') ?: null,
            'type' => input('type'),
            'start_date' => input('start_date') ?: null,
            'end_date' => input('end_date') ?: null,
            'value' => input('value') ? (float) input('value') : null,
            'status' => input('status'),
            'terms' => input('terms')
        ];

        // Handle document upload
        if (!empty($_FILES['document']['name'])) {
            $path = uploadFile($_FILES['document'], 'contracts', ALLOWED_DOC_TYPES);
            if ($path) {
                $data['document_path'] = $path;
            }
        }

        Contract::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'contract', $id);

        Session::flash('success', 'Contract updated successfully.');
        redirect('contracts/view?id=' . $id);
    }

    /**
     * View contract
     */
    public function view(): void
    {
        Auth::requirePermission('contracts', 'view');

        $id = (int) input('id');
        $contract = Contract::findWithDetails($id);

        if (!$contract) {
            Router::notFound();
        }

        view('contracts/view', [
            'pageTitle' => $contract['title'],
            'contract' => $contract
        ]);
    }

    /**
     * Archive contract
     */
    public function archive(): void
    {
        Auth::requirePermission('contracts', 'archive');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('contracts');
        }

        Contract::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'contract', $id);

        Session::flash('success', 'Contract archived successfully.');
        redirect('contracts');
    }

    /**
     * Print contract list
     */
    public function printList(): void
    {
        Auth::requirePermission('contracts', 'print');

        printView('contract_list_print', [
            'pageTitle' => 'Contract List',
            'contracts' => Contract::allWithRelations(),
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print contract details
     */
    public function printDetails(): void
    {
        Auth::requirePermission('contracts', 'print');

        $id = (int) input('id');
        $contract = Contract::findWithDetails($id);

        if (!$contract) {
            Router::notFound();
        }

        printView('contract_print', [
            'pageTitle' => 'Contract: ' . ($contract['title'] ?? $contract['contract_number'] ?? ''),
            'contract' => $contract,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }
}
