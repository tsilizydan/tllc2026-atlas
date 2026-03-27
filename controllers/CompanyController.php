<?php
/**
 * TSILIZY CORE - Company Controller
 */

class CompanyController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * Show company profile
     */
    public function index(): void
    {
        Auth::requirePermission('company', 'view');

        $company = CompanyProfile::get();
        $services = Service::all();

        view('company/index', [
            'pageTitle' => 'Company Configuration',
            'company' => $company,
            'services' => $services
        ]);
    }

    /**
     * Update company profile
     */
    public function update(): void
    {
        Auth::requirePermission('company', 'edit');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('company');
        }

        $data = [
            'company_name' => input('company_name'),
            'legal_name' => input('legal_name'),
            'tax_id' => input('tax_id'),
            'registration_number' => input('registration_number'),
            'address' => input('address'),
            'city' => input('city'),
            'country' => input('country'),
            'phone' => input('phone'),
            'email' => input('email'),
            'website' => input('website'),
            'primary_color' => input('primary_color', '#C9A227'),
            'secondary_color' => input('secondary_color', '#000000'),
            'social_facebook' => input('social_facebook'),
            'social_twitter' => input('social_twitter'),
            'social_linkedin' => input('social_linkedin'),
            'social_instagram' => input('social_instagram'),
            'footer_text' => input('footer_text')
        ];

        // Handle logo upload
        if (!empty($_FILES['logo']['name'])) {
            $path = uploadFile($_FILES['logo'], 'company', ALLOWED_IMAGE_TYPES);
            if ($path) {
                $data['logo_path'] = $path;
            }
        }

        // Handle favicon upload
        if (!empty($_FILES['favicon']['name'])) {
            $path = uploadFile($_FILES['favicon'], 'company', ALLOWED_IMAGE_TYPES);
            if ($path) {
                $data['favicon_path'] = $path;
            }
        }

        CompanyProfile::updateProfile($data);
        Auth::logActivity(Auth::id(), 'update', 'company_profile', 1);

        Session::flash('success', 'Company profile updated successfully.');
        redirect('company');
    }

    /**
     * List services
     */
    public function services(): void
    {
        Auth::requirePermission('company', 'view');

        $services = Service::all();

        view('company/services/index', [
            'pageTitle' => 'Services',
            'services' => $services
        ]);
    }

    /**
     * Create service form
     */
    public function createService(): void
    {
        Auth::requirePermission('company', 'edit');

        view('company/services/create', [
            'pageTitle' => 'Add Service'
        ]);
    }

    /**
     * Store service
     */
    public function storeService(): void
    {
        Auth::requirePermission('company', 'edit');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('company/services');
        }

        $validator = Validator::make()
            ->required('name', 'Service Name');

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('company/services/create');
        }

        $data = [
            'name' => input('name'),
            'description' => input('description'),
            'icon' => input('icon'),
            'price_range' => input('price_range'),
            'is_active' => input('is_active') ? 1 : 0,
            'sort_order' => (int) input('sort_order', 0)
        ];

        Service::create($data);
        Session::flash('success', 'Service created successfully.');
        redirect('company/services');
    }

    /**
     * Edit service form
     */
    public function editService(): void
    {
        Auth::requirePermission('company', 'edit');

        $id = (int) input('id');
        $service = Service::findOrFail($id);

        view('company/services/edit', [
            'pageTitle' => 'Edit Service',
            'service' => $service
        ]);
    }

    /**
     * Update service
     */
    public function updateService(): void
    {
        Auth::requirePermission('company', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('company/services');
        }

        $data = [
            'name' => input('name'),
            'description' => input('description'),
            'icon' => input('icon'),
            'price_range' => input('price_range'),
            'is_active' => input('is_active') ? 1 : 0,
            'sort_order' => (int) input('sort_order', 0)
        ];

        Service::update($id, $data);
        Session::flash('success', 'Service updated successfully.');
        redirect('company/services');
    }

    /**
     * Delete service
     */
    public function deleteService(): void
    {
        Auth::requirePermission('company', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('company/services');
        }

        Service::delete($id);
        Session::flash('success', 'Service deleted successfully.');
        redirect('company/services');
    }

    /**
     * Print services
     */
    public function printServices(): void
    {
        Auth::requirePermission('company', 'view');

        printView('service_list_print', [
            'pageTitle' => 'Service Catalog',
            'services' => Service::active(),
            'company' => CompanyProfile::get() ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Show company profile page
     */
    public function profile(): void
    {
        Auth::requirePermission('company', 'view');

        view('company/profile', [
            'pageTitle' => 'Company Profile',
            'company' => CompanyProfile::get()
        ]);
    }

    /**
     * Show branding page
     */
    public function branding(): void
    {
        Auth::requirePermission('company', 'view');

        view('company/branding', [
            'pageTitle' => 'Company Branding',
            'company' => CompanyProfile::get()
        ]);
    }

    /**
     * Update branding
     */
    public function updateBranding(): void
    {
        Auth::requirePermission('company', 'edit');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('company/branding');
        }

        $data = [
            'primary_color' => input('primary_color', '#C9A227'),
            'secondary_color' => input('secondary_color', '#000000'),
            'accent_color' => input('accent_color', '#C9A227'),
            'footer_text' => input('footer_text')
        ];

        // Handle logo upload
        if (!empty($_FILES['logo']['name'])) {
            $path = uploadFile($_FILES['logo'], 'company', ALLOWED_IMAGE_TYPES);
            if ($path) {
                $data['logo'] = $path;
            }
        }

        // Handle favicon upload
        if (!empty($_FILES['favicon']['name'])) {
            $path = uploadFile($_FILES['favicon'], 'company', ALLOWED_IMAGE_TYPES);
            if ($path) {
                $data['favicon'] = $path;
            }
        }

        CompanyProfile::updateProfile($data);
        Auth::logActivity(Auth::id(), 'update', 'company_branding', 1);

        Session::flash('success', 'Branding updated successfully.');
        redirect('company/branding');
    }
}

