<?php
/**
 * TSILIZY CORE - RBAC (Role-Based Access Control)
 * Permission definitions and management
 */

class RBAC
{
    /**
     * Default roles with their permissions
     */
    public static function getDefaultRoles(): array
    {
        return [
            'super_admin' => [
                'name' => 'Super Administrator',
                'description' => 'Full system access with user management',
                'permissions' => self::getAllPermissions()
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'All modules except system configuration',
                'permissions' => [
                    'invoices' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'clients' => ['view', 'create', 'edit', 'delete', 'print'],
                    'projects' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'hr' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'contracts' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'partners' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'finance' => ['view', 'create', 'edit', 'delete', 'print', 'reports'],
                    'company' => ['view'],
                    'users' => ['view'],
                'assets' => ['view', 'create', 'edit', 'delete', 'print', 'archive']
                ]
            ],
            'manager' => [
                'name' => 'Manager',
                'description' => 'Projects, clients, contracts, and reports',
                'permissions' => [
                    'invoices' => ['view', 'create', 'edit', 'print'],
                    'clients' => ['view', 'create', 'edit', 'print'],
                    'projects' => ['view', 'create', 'edit', 'print'],
                    'contracts' => ['view', 'create', 'edit', 'print'],
                    'partners' => ['view', 'print'],
                    'assets' => ['view', 'print'],
                    'finance' => ['view', 'reports'],
                    'company' => ['view']
                ]
            ],
            'hr_manager' => [
                'name' => 'HR Manager',
                'description' => 'HR module, employee records, paychecks, and assets',
                'permissions' => [
                    'hr' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'assets' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
                    'clients' => ['view'],
                    'projects' => ['view'],
                    'company' => ['view']
                ]
            ],
            'finance' => [
                'name' => 'Finance',
                'description' => 'Finance module, invoices, and reports',
                'permissions' => [
                    'invoices' => ['view', 'create', 'edit', 'print', 'archive'],
                    'clients' => ['view', 'print'],
                    'finance' => ['view', 'create', 'edit', 'delete', 'print', 'reports'],
                    'company' => ['view']
                ]
            ],
            'staff' => [
                'name' => 'Staff',
                'description' => 'Read-only access to assigned items',
                'permissions' => [
                    'invoices' => ['view'],
                    'clients' => ['view'],
                    'projects' => ['view'],
                    'assets' => ['view'],
                    'company' => ['view']
                ]
            ]
        ];
    }

    /**
     * Get all available permissions
     */
    public static function getAllPermissions(): array
    {
        return [
            'invoices' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
            'clients' => ['view', 'create', 'edit', 'delete', 'print'],
            'projects' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
            'hr' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
            'contracts' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
            'partners' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
            'assets' => ['view', 'create', 'edit', 'delete', 'print', 'archive'],
            'finance' => ['view', 'create', 'edit', 'delete', 'print', 'reports'],
            'company' => ['view', 'edit'],
            'users' => ['view', 'create', 'edit', 'delete'],
            'logs' => ['view']
        ];
    }

    /**
     * Get readable module names
     */
    public static function getModuleNames(): array
    {
        return [
            'invoices' => 'Invoice Management',
            'clients' => 'Client Management',
            'projects' => 'Project Management',
            'hr' => 'Human Resources',
            'contracts' => 'Contract Management',
            'partners' => 'Partner Management',
            'assets' => 'Asset Management',
            'finance' => 'Finance Management',
            'company' => 'Company Configuration',
            'users' => 'User Management',
            'logs' => 'Activity Logs'
        ];
    }

    /**
     * Get readable action names
     */
    public static function getActionNames(): array
    {
        return [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'print' => 'Print',
            'archive' => 'Archive',
            'reports' => 'View Reports'
        ];
    }

    /**
     * Check if a role has a specific permission
     */
    public static function roleHasPermission(string $roleSlug, string $module, string $action): bool
    {
        $roles = self::getDefaultRoles();
        
        if (!isset($roles[$roleSlug])) {
            return false;
        }

        $permissions = $roles[$roleSlug]['permissions'];
        
        return isset($permissions[$module]) && in_array($action, $permissions[$module]);
    }

    /**
     * Seed roles into database
     */
    public static function seedRoles(): void
    {
        $roles = self::getDefaultRoles();
        
        foreach ($roles as $slug => $role) {
            $exists = Database::exists('roles', 'slug = ?', [$slug]);
            
            if (!$exists) {
                Database::insert('roles', [
                    'name' => $role['name'],
                    'slug' => $slug,
                    'description' => $role['description'],
                    'permissions' => json_encode($role['permissions']),
                    'created_at' => date(DATETIME_FORMAT),
                    'updated_at' => date(DATETIME_FORMAT)
                ]);
            }
        }
    }

    /**
     * Get all roles from database
     */
    public static function getAllRoles(): array
    {
        return Database::fetchAll("SELECT * FROM roles ORDER BY id ASC");
    }

    /**
     * Get role by slug
     */
    public static function getRoleBySlug(string $slug): ?array
    {
        return Database::fetch("SELECT * FROM roles WHERE slug = ?", [$slug]);
    }

    /**
     * Get role permissions
     */
    public static function getRolePermissions(int $roleId): array
    {
        $role = Database::fetch("SELECT permissions FROM roles WHERE id = ?", [$roleId]);
        return $role ? json_decode($role['permissions'], true) : [];
    }
}
