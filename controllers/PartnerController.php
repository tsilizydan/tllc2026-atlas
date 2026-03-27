<?php
/**
 * TSILIZY CORE - Partner Controller
 */

class PartnerController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * List all partners
     */
    public function index(): void
    {
        Auth::requirePermission('partners', 'view');

        $partners = Partner::all();

        view('partners/index', [
            'pageTitle' => 'Partners',
            'partners' => $partners,
            'stats' => Partner::getStats()
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        Auth::requirePermission('partners', 'create');

        view('partners/create', [
            'pageTitle' => 'Add Partner'
        ]);
    }

    /**
     * Store new partner
     */
    public function store(): void
    {
        Auth::requirePermission('partners', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('partners');
        }

        $validator = Validator::make()
            ->required('company_name', 'Company Name')
            ->email('email');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('partners/create');
        }

        $data = [
            'company_name' => input('company_name'),
            'contact_name' => input('contact_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'address' => input('address'),
            'website' => input('website'),
            'partnership_type' => input('partnership_type'),
            'notes' => input('notes'),
            'status' => input('status', 'active')
        ];

        $id = Partner::create($data);
        Auth::logActivity(Auth::id(), 'create', 'partner', $id);

        clearOldInput();
        Session::flash('success', 'Partner created successfully.');
        redirect('partners/view?id=' . $id);
    }

    /**
     * Show edit form
     */
    public function edit(): void
    {
        Auth::requirePermission('partners', 'edit');

        $id = (int) input('id');
        $partner = Partner::findOrFail($id);

        view('partners/edit', [
            'pageTitle' => 'Edit Partner',
            'partner' => $partner
        ]);
    }

    /**
     * Update partner
     */
    public function update(): void
    {
        Auth::requirePermission('partners', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('partners');
        }

        $data = [
            'company_name' => input('company_name'),
            'contact_name' => input('contact_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'address' => input('address'),
            'website' => input('website'),
            'partnership_type' => input('partnership_type'),
            'notes' => input('notes'),
            'status' => input('status')
        ];

        Partner::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'partner', $id);

        Session::flash('success', 'Partner updated successfully.');
        redirect('partners/view?id=' . $id);
    }

    /**
     * View partner
     */
    public function view(): void
    {
        Auth::requirePermission('partners', 'view');

        $id = (int) input('id');
        $partner = Partner::findWithContracts($id);

        if (!$partner) {
            Router::notFound();
        }

        view('partners/view', [
            'pageTitle' => $partner['company_name'],
            'partner' => $partner
        ]);
    }

    /**
     * Archive partner
     */
    public function archive(): void
    {
        Auth::requirePermission('partners', 'archive');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('partners');
        }

        Partner::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'partner', $id);

        Session::flash('success', 'Partner archived successfully.');
        redirect('partners');
    }

    /**
     * Print partner list
     */
    public function printList(): void
    {
        Auth::requirePermission('partners', 'print');

        printView('partner_list_print', [
            'pageTitle' => 'Partner Directory',
            'partners' => Partner::all(),
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print partner profile
     */
    public function printProfile(): void
    {
        Auth::requirePermission('partners', 'print');

        $id = (int) input('id');
        $partner = Partner::findWithContracts($id);

        if (!$partner) {
            Router::notFound();
        }

        printView('partner_profile_print', [
            'pageTitle' => ($partner['company_name'] ?? 'Partner') . ' - Profile',
            'partner' => $partner,
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }
}
