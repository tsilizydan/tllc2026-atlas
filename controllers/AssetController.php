<?php
/**
 * TSILIZY CORE - Asset Controller
 * Facilities / Asset Management
 */

class AssetController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * List all assets
     */
    public function index(): void
    {
        Auth::requirePermission('assets', 'view');

        $search = input('search', '');
        $status = input('status', '');
        $categoryId = input('category', '');

        $where = '1=1';
        $params = [];

        if ($search !== '') {
            $where .= ' AND (a.name LIKE ? OR a.asset_tag LIKE ? OR a.serial_number LIKE ?)';
            $term = "%{$search}%";
            $params = array_merge($params, [$term, $term, $term]);
        }
        if ($status !== '') {
            $where .= ' AND a.status = ?';
            $params[] = $status;
        }
        if ($categoryId !== '') {
            $where .= ' AND a.category_id = ?';
            $params[] = (int) $categoryId;
        }

        $where .= ' AND a.is_archived = 0';

        $assets = Database::fetchAll(
            "SELECT a.*, ac.name as category_name, ac.icon as category_icon,
                    CONCAT(e.first_name, ' ', e.last_name) as employee_name
             FROM assets a
             LEFT JOIN asset_categories ac ON a.category_id = ac.id
             LEFT JOIN employees e ON a.employee_id = e.id
             WHERE {$where}
             ORDER BY a.created_at DESC",
            $params
        );

        try {
            $stats = Asset::getStats();
        } catch (Throwable $e) {
            $stats = ['total' => 0, 'available' => 0, 'assigned' => 0, 'in_repair' => 0, 'retired' => 0, 'lost' => 0, 'total_value' => 0, 'categories' => 0];
        }

        view('assets/index', [
            'pageTitle' => 'Assets',
            'assets' => $assets,
            'stats' => $stats,
            'categories' => AssetCategory::dropdown(),
            'search' => $search,
            'status' => $status,
            'categoryId' => $categoryId
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        Auth::requirePermission('assets', 'create');

        view('assets/create', [
            'pageTitle' => 'Add Asset',
            'assetTag' => Asset::generateTag(),
            'categories' => AssetCategory::dropdown(),
            'employees' => Employee::dropdown()
        ]);
    }

    /**
     * Store new asset
     */
    public function store(): void
    {
        Auth::requirePermission('assets', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('assets');
        }

        $validator = Validator::make()
            ->required('asset_tag', 'Asset Tag')
            ->required('name', 'Asset Name')
            ->required('category_id', 'Category')
            ->unique('asset_tag', 'assets');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('assets/create');
        }

        $employeeId = input('employee_id') ? (int) input('employee_id') : null;
        $status = $employeeId ? 'assigned' : input('status', 'available');

        $data = [
            'asset_tag' => input('asset_tag'),
            'name' => input('name'),
            'category_id' => (int) input('category_id'),
            'description' => input('description'),
            'serial_number' => input('serial_number'),
            'purchase_date' => input('purchase_date') ?: null,
            'purchase_price' => input('purchase_price') ? (float) input('purchase_price') : null,
            'warranty_expiry' => input('warranty_expiry') ?: null,
            'location' => input('location'),
            'status' => $status,
            'employee_id' => $employeeId,
            'assigned_at' => $employeeId ? date(DATETIME_FORMAT) : null,
            'notes' => input('notes')
        ];

        $id = Asset::create($data);
        Auth::logActivity(Auth::id(), 'create', 'asset', $id);

        clearOldInput();
        Session::flash('success', 'Asset created successfully.');
        redirect('assets/view?id=' . $id);
    }

    /**
     * Show asset details
     */
    public function view(): void
    {
        Auth::requirePermission('assets', 'view');

        $id = (int) (Router::getParam('id') ?? input('id', 0));
        if (!$id) {
            redirect('assets');
        }

        $asset = Asset::findWithDetails($id);
        if (!$asset) {
            Session::flash('error', 'Asset not found.');
            redirect('assets');
        }

        view('assets/view', [
            'pageTitle' => 'Asset: ' . ($asset['name'] ?? 'Details'),
            'asset' => $asset,
            'employee' => $asset['employee_id'] ? Employee::find($asset['employee_id']) : null
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(): void
    {
        Auth::requirePermission('assets', 'edit');

        $id = (int) (Router::getParam('id') ?? input('id', 0));
        if (!$id) {
            redirect('assets');
        }

        $asset = Asset::findWithDetails($id);
        if (!$asset) {
            Session::flash('error', 'Asset not found.');
            redirect('assets');
        }

        view('assets/edit', [
            'pageTitle' => 'Edit Asset',
            'asset' => $asset,
            'categories' => AssetCategory::dropdown(),
            'employees' => Employee::dropdown()
        ]);
    }

    /**
     * Update asset
     */
    public function update(): void
    {
        Auth::requirePermission('assets', 'edit');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('assets');
        }

        $id = (int) input('id', 0);
        if (!$id) {
            redirect('assets');
        }

        $asset = Asset::find($id);
        if (!$asset) {
            Session::flash('error', 'Asset not found.');
            redirect('assets');
        }

        $validator = Validator::make()
            ->required('asset_tag', 'Asset Tag')
            ->required('name', 'Asset Name')
            ->required('category_id', 'Category')
            ->unique('asset_tag', 'assets', 'asset_tag', $id);

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('assets/edit?id=' . $id);
        }

        $employeeId = input('employee_id') ? (int) input('employee_id') : null;
        $status = input('status', $asset['status']);
        if ($employeeId) {
            $status = 'assigned';
        } elseif ($status === 'assigned') {
            $status = 'available';
        }

        $data = [
            'asset_tag' => input('asset_tag'),
            'name' => input('name'),
            'category_id' => (int) input('category_id'),
            'description' => input('description'),
            'serial_number' => input('serial_number'),
            'purchase_date' => input('purchase_date') ?: null,
            'purchase_price' => input('purchase_price') ? (float) input('purchase_price') : null,
            'warranty_expiry' => input('warranty_expiry') ?: null,
            'location' => input('location'),
            'status' => $status,
            'employee_id' => $employeeId,
            'assigned_at' => $employeeId ? ($asset['assigned_at'] ?? date(DATETIME_FORMAT)) : null,
            'notes' => input('notes')
        ];

        Asset::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'asset', $id);

        clearOldInput();
        Session::flash('success', 'Asset updated successfully.');
        redirect('assets/view?id=' . $id);
    }

    /**
     * Assign asset to employee
     */
    public function assign(): void
    {
        Auth::requirePermission('assets', 'edit');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('assets');
        }

        $id = (int) input('id', 0);
        $employeeId = input('employee_id') ? (int) input('employee_id') : null;

        if (!$id) {
            redirect('assets');
        }

        $asset = Asset::find($id);
        if (!$asset) {
            Session::flash('error', 'Asset not found.');
            redirect('assets');
        }

        Asset::assign($id, $employeeId);
        Auth::logActivity(Auth::id(), 'assign', 'asset', $id);

        Session::flash('success', $employeeId ? 'Asset assigned successfully.' : 'Asset unassigned.');
        redirect('assets/view?id=' . $id);
    }

    /**
     * Archive (soft delete) asset
     */
    public function delete(): void
    {
        Auth::requirePermission('assets', 'delete');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('assets');
        }

        $id = (int) input('id', 0);
        if (!$id) {
            redirect('assets');
        }

        $asset = Asset::find($id);
        if (!$asset) {
            Session::flash('error', 'Asset not found.');
            redirect('assets');
        }

        Asset::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'asset', $id);

        Session::flash('success', 'Asset archived successfully.');
        redirect('assets');
    }

    /**
     * Print asset list
     */
    public function printList(): void
    {
        Auth::requirePermission('assets', 'print');

        $assets = Asset::allWithRelations(false);
        $stats = Asset::getStats();

        printView('prints/asset_list_print', [
            'pageTitle' => 'Asset Register',
            'assets' => $assets,
            'stats' => $stats,
            'company' => CompanyProfile::get() ?? []
        ]);
    }

    /**
     * Print single asset
     */
    public function printDetails(): void
    {
        Auth::requirePermission('assets', 'print');

        $id = (int) (Router::getParam('id') ?? input('id', 0));
        if (!$id) {
            redirect('assets');
        }

        $asset = Asset::findWithDetails($id);
        if (!$asset) {
            Session::flash('error', 'Asset not found.');
            redirect('assets');
        }

        printView('prints/asset_print', [
            'pageTitle' => 'Asset: ' . ($asset['name'] ?? 'Details'),
            'asset' => $asset,
            'company' => CompanyProfile::get() ?? []
        ]);
    }
}
