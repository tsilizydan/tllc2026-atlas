<?php
/**
 * User Controller
 * Handles user management operations
 */

class UserController
{
    /**
     * List all users
     */
    public function index(): void
    {
        Auth::requirePermission('users', 'view');
        
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;
        
        $conditions = ['deleted_at IS NULL'];
        $params = [];
        
        if ($search) {
            $conditions[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }
        
        if ($role) {
            $conditions[] = "r.slug = ?";
            $params[] = $role;
        }
        
        if ($status === 'active') {
            $conditions[] = "u.is_active = 1";
        } elseif ($status === 'inactive') {
            $conditions[] = "u.is_active = 0";
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM users u LEFT JOIN roles r ON u.role_id = r.id WHERE {$whereClause}";
        $total = Database::fetch($countSql, $params)['total'];
        
        // Get paginated users
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT u.*, r.name as role_name, r.slug as role_slug 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE {$whereClause}
                ORDER BY u.created_at DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        
        $users = Database::fetchAll($sql, $params);
        
        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => ceil($total / $perPage)
        ];
        
        view('users/index', compact('users', 'pagination'));
    }
    
    /**
     * Show create user form
     */
    public function create(): void
    {
        Auth::requirePermission('users', 'create');
        
        $roles = Database::fetchAll("SELECT id, name, slug FROM roles ORDER BY name");
        
        view('users/create', compact('roles'));
    }
    
    /**
     * Store a new user
     */
    public function store(): void
    {
        Auth::requirePermission('users', 'create');
        Session::validateCsrf();
        
        $validator = Validator::make($_POST)
            ->required('first_name', 'First Name')
            ->required('last_name', 'Last Name')
            ->required('email', 'Email')
            ->required('password', 'Password')
            ->required('role_id', 'Role')
            ->email('email')
            ->min('password', 8)
            ->unique('email', 'users', 'email');
        
        if ($validator->fails()) {
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('users/create');
        }
        
        // Check password confirmation
        if ($_POST['password'] !== $_POST['password_confirmation']) {
            Session::flash('error', 'Passwords do not match.');
            redirect('users/create');
        }
        
        $data = [
            'first_name' => sanitize($_POST['first_name']),
            'last_name' => sanitize($_POST['last_name']),
            'email' => sanitize($_POST['email']),
            'username' => sanitize($_POST['username'] ?? strtolower($_POST['first_name'])),
            'password_hash' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role_id' => (int)$_POST['role_id'],
            'phone' => sanitize($_POST['phone'] ?? ''),
            'department' => sanitize($_POST['department'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $uploaded = uploadFile($_FILES['avatar'], 'avatars', ['jpg', 'jpeg', 'png', 'gif']);
            if ($uploaded !== null) {
                $data['avatar'] = $uploaded;
            }
        }
        
        $userId = Database::insert('users', $data);
        
        if ($userId) {
            // Send welcome email if requested
            if (!empty($_POST['send_welcome_email'])) {
                // Email sending logic would go here
            }
            
            Session::flash('success', 'User created successfully.');
            redirect('users');
        } else {
            Session::flash('error', 'Failed to create user.');
            redirect('users/create');
        }
    }
    
    /**
     * Show edit user form
     */
    public function edit(): void
    {
        Auth::requirePermission('users', 'edit');
        
        $id = (int)($_GET['id'] ?? 0);
        
        $user = Database::fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug 
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ? AND u.deleted_at IS NULL",
            [$id]
        );
        
        if (!$user) {
            Session::flash('error', 'User not found.');
            redirect('users');
        }
        
        $roles = Database::fetchAll("SELECT id, name, slug FROM roles ORDER BY name");
        
        view('users/edit', compact('user', 'roles'));
    }
    
    /**
     * Update a user
     */
    public function update(): void
    {
        Auth::requirePermission('users', 'edit');
        Session::validateCsrf();
        
        $id = (int)($_GET['id'] ?? 0);
        
        $user = Database::fetch("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$user) {
            Session::flash('error', 'User not found.');
            redirect('users');
        }
        
        $validator = Validator::make($_POST)
            ->required('first_name', 'First Name')
            ->required('last_name', 'Last Name')
            ->required('email', 'Email')
            ->required('role_id', 'Role')
            ->email('email');
        
        if ($validator->fails()) {
            Session::flash('error', array_values($validator->errors())[0]);
            redirect("users/edit?id={$id}");
        }
        
        // Check if email is unique (excluding current user)
        $existingUser = Database::fetch(
            "SELECT id FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL",
            [$_POST['email'], $id]
        );
        
        if ($existingUser) {
            Session::flash('error', 'Email is already in use.');
            redirect("users/edit?id={$id}");
        }
        
        $data = [
            'first_name' => sanitize($_POST['first_name']),
            'last_name' => sanitize($_POST['last_name']),
            'email' => sanitize($_POST['email']),
            'username' => sanitize($_POST['username'] ?? $user['username']),
            'role_id' => (int)$_POST['role_id'],
            'phone' => sanitize($_POST['phone'] ?? ''),
            'department' => sanitize($_POST['department'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Prevent user from deactivating themselves
        if ($id === Auth::id()) {
            $data['is_active'] = 1;
            $data['role_id'] = $user['role_id'];
        }
        
        // Handle password change
        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['password_confirmation']) {
                Session::flash('error', 'Passwords do not match.');
                redirect("users/edit?id={$id}");
            }
            if (strlen($_POST['password']) < 8) {
                Session::flash('error', 'Password must be at least 8 characters.');
                redirect("users/edit?id={$id}");
            }
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $uploaded = uploadFile($_FILES['avatar'], 'avatars', ['jpg', 'jpeg', 'png', 'gif']);
            if ($uploaded !== null) {
                // Delete old avatar if exists
                if (!empty($user['avatar'])) {
                    deleteFile($user['avatar']);
                }
                $data['avatar'] = $uploaded;
            }
        }
        
        // Handle avatar removal
        if (!empty($_POST['remove_avatar']) && !empty($user['avatar'])) {
            deleteFile($user['avatar']);
            $data['avatar'] = null;
        }
        
        Database::update('users', $data, 'id = ?', [$id]);
        
        Session::flash('success', 'User updated successfully.');
        redirect('users');
    }
    
    /**
     * Toggle user active status
     */
    public function toggle(): void
    {
        Auth::requirePermission('users', 'edit');
        Session::validateCsrf();
        
        $id = (int)($_GET['id'] ?? 0);
        
        // Prevent self-toggle
        if ($id === Auth::id()) {
            Session::flash('error', 'You cannot deactivate your own account.');
            redirect('users');
        }
        
        $user = Database::fetch("SELECT is_active FROM users WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$user) {
            Session::flash('error', 'User not found.');
            redirect('users');
        }
        
        $newStatus = $user['is_active'] ? 0 : 1;
        
        Database::update('users', [
            'is_active' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$id]);
        
        Session::flash('success', 'User status updated.');
        redirect('users');
    }
    
    /**
     * Delete a user (soft delete)
     */
    public function delete(): void
    {
        Auth::requirePermission('users', 'delete');
        Session::validateCsrf();
        
        $id = (int)($_GET['id'] ?? 0);
        
        // Prevent self-deletion
        if ($id === Auth::id()) {
            Session::flash('error', 'You cannot delete your own account.');
            redirect('users');
        }
        
        $user = Database::fetch("SELECT * FROM users WHERE id = ? AND deleted_at IS NULL", [$id]);
        
        if (!$user) {
            Session::flash('error', 'User not found.');
            redirect('users');
        }
        
        // Soft delete
        Database::update('users', [
            'deleted_at' => date('Y-m-d H:i:s'),
            'is_active' => 0
        ], 'id = ?', [$id]);
        
        Session::flash('success', 'User deleted successfully.');
        redirect('users');
    }


    /**
     * Show current user profile
     */
    public function profile(): void
    {
        Auth::requireAuth();
        
        $user = Database::fetch(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ?",
            [Auth::id()]
        );
        
        view('users/profile', ['user' => $user]);
    }

    /**
     * Update current user profile
     */
    public function updateProfile(): void
    {
        Auth::requireAuth();
        Session::validateCsrf();
        
        $id = Auth::id();
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$id]);
        
        $data = [
            'first_name' => input('first_name'),
            'last_name' => input('last_name'),
            'email' => input('email'),
            'phone' => input('phone'),
            'updated_at' => date(DATETIME_FORMAT)
        ];
        
        // Handle avatar
        if (!empty($_FILES['avatar']['name'])) {
            $uploaded = uploadFile($_FILES['avatar'], 'avatars', ['jpg', 'jpeg', 'png', 'gif']);
            if ($uploaded !== null) {
                $data['avatar'] = $uploaded;
            }
        }
        
        // Handle password
        if (!empty($_POST['password'])) {
            if ($_POST['password'] === $_POST['password_confirmation']) {
                $data['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
        }
        
        Database::update('users', $data, 'id = ?', [$id]);
        
        Session::flash('success', 'Profile updated successfully.');
        redirect('users/profile');
    }
}
