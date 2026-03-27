<?php
/**
 * TSILIZY CORE - Client Controller
 */

class ClientController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * List all clients
     */
    public function index(): void
    {
        Auth::requirePermission('clients', 'view');

        $page = (int) input('page', 1);
        $search = input('search', '');
        $status = input('status', '');

        $where = 'is_archived = 0';
        $params = [];

        if ($search) {
            $where .= " AND (company_name LIKE ? OR contact_name LIKE ? OR email LIKE ?)";
            $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
        }

        if ($status) {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $result = Client::paginate($page, ITEMS_PER_PAGE, $where, $params);

        view('clients/index', [
            'pageTitle' => 'Clients',
            'clients' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'status' => $status,
            'stats' => Client::getStats()
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        Auth::requirePermission('clients', 'create');

        view('clients/create', [
            'pageTitle' => 'Add New Client'
        ]);
    }

    /**
     * Store new client
     */
    public function store(): void
    {
        Auth::requirePermission('clients', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('clients');
        }

        $validator = Validator::make()
            ->required('company_name', 'Company Name')
            ->maxLength('company_name', 150)
            ->email('email')
            ->unique('email', 'clients');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('clients/create');
        }

        $data = [
            'company_name' => input('company_name'),
            'contact_name' => input('contact_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'address' => input('address'),
            'city' => input('city'),
            'country' => input('country'),
            'website' => input('website'),
            'tax_id' => input('tax_id'),
            'notes' => input('notes'),
            'status' => input('status', 'active')
        ];

        $id = Client::create($data);
        Auth::logActivity(Auth::id(), 'create', 'client', $id);

        clearOldInput();
        Session::flash('success', 'Client created successfully.');
        redirect('clients/view?id=' . $id);
    }

    /**
     * Show edit form
     */
    public function edit(): void
    {
        Auth::requirePermission('clients', 'edit');

        $id = (int) input('id');
        $client = Client::findOrFail($id);

        view('clients/edit', [
            'pageTitle' => 'Edit Client',
            'client' => $client
        ]);
    }

    /**
     * Update client
     */
    public function update(): void
    {
        Auth::requirePermission('clients', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('clients');
        }

        $client = Client::findOrFail($id);

        $validator = Validator::make()
            ->required('company_name', 'Company Name')
            ->maxLength('company_name', 150)
            ->email('email')
            ->unique('email', 'clients', 'email', $id);

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('clients/edit?id=' . $id);
        }

        $data = [
            'company_name' => input('company_name'),
            'contact_name' => input('contact_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'address' => input('address'),
            'city' => input('city'),
            'country' => input('country'),
            'website' => input('website'),
            'tax_id' => input('tax_id'),
            'notes' => input('notes'),
            'status' => input('status', 'active')
        ];

        Client::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'client', $id);

        clearOldInput();
        Session::flash('success', 'Client updated successfully.');
        redirect('clients/view?id=' . $id);
    }

    /**
     * View client
     */
    public function view(): void
    {
        Auth::requirePermission('clients', 'view');

        $id = (int) input('id');
        $client = Client::findWithRelations($id);

        if (!$client) {
            Router::notFound();
        }

        $client['total_revenue'] = Client::getTotalRevenue($id);

        view('clients/view', [
            'pageTitle' => $client['company_name'],
            'client' => $client
        ]);
    }

    /**
     * Delete client
     */
    public function delete(): void
    {
        Auth::requirePermission('clients', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('clients');
        }

        Client::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'client', $id);

        Session::flash('success', 'Client archived successfully.');
        redirect('clients');
    }

    /**
     * Print client list
     */
    public function printList(): void
    {
        Auth::requirePermission('clients', 'print');

        $clients = Client::all();
        $company = CompanyProfile::get();

        printView('client_list_print', [
            'pageTitle' => 'Client Directory',
            'clients' => $clients ?? [],
            'company' => $company ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print client profile
     */
    public function printProfile(): void
    {
        Auth::requirePermission('clients', 'print');

        $id = (int) input('id');
        $client = Client::findWithRelations($id);

        if (!$client) {
            Router::notFound();
        }

        $company = CompanyProfile::get();

        printView('client_profile_print', [
            'pageTitle' => ($client['company_name'] ?? 'Client') . ' - Profile',
            'client' => $client,
            'company' => $company ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }
}
