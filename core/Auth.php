<?php
/**
 * TSILIZY CORE - Authentication Helper
 * User authentication and authorization
 */

class Auth
{
    private static ?array $user = null;
    private static ?array $permissions = null;

    /**
     * Attempt to authenticate user
     */
    public static function attempt(string $email, string $password, bool $remember = false): bool
    {
        // Check for login throttling
        if (self::isLockedOut($email)) {
            return false;
        }

        $user = Database::fetch(
            "SELECT u.*, r.permissions, r.name as role_name, r.slug as role_slug 
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             WHERE u.email = ? AND u.is_active = 1",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::recordFailedAttempt($email);
            return false;
        }

        // Clear failed attempts
        self::clearFailedAttempts($email);

        // Update last login
        Database::update('users', ['last_login' => date(DATETIME_FORMAT)], 'id = ?', [$user['id']]);

        // Set session
        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role_slug']);

        // Handle remember me
        if ($remember) {
            self::createRememberToken($user['id']);
        }

        // Log activity
        self::logActivity($user['id'], 'login', 'user', $user['id']);

        return true;
    }

    /**
     * Log out current user
     */
    public static function logout(): void
    {
        if (self::check()) {
            self::logActivity(self::id(), 'logout', 'user', self::id());
            self::clearRememberToken();
        }
        
        Session::destroy();
        self::$user = null;
        self::$permissions = null;
    }

    /**
     * Check if user is authenticated
     */
    public static function check(): bool
    {
        if (Session::has('user_id')) {
            return true;
        }

        // Check remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            return self::validateRememberToken($_COOKIE['remember_token']);
        }

        return false;
    }

    /**
     * Check if user is guest
     */
    public static function guest(): bool
    {
        return !self::check();
    }

    /**
     * Get authenticated user ID
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Get authenticated user data
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        if (self::$user === null) {
            self::$user = Database::fetch(
                "SELECT u.*, r.name as role_name, r.slug as role_slug, r.permissions
                 FROM users u 
                 LEFT JOIN roles r ON u.role_id = r.id 
                 WHERE u.id = ?",
                [self::id()]
            );
        }

        return self::$user;
    }

    /**
     * Get user permissions
     */
    public static function permissions(): array
    {
        if (self::$permissions === null) {
            $user = self::user();
            self::$permissions = $user ? json_decode($user['permissions'] ?? '[]', true) : [];
        }
        return self::$permissions;
    }

    /**
     * Check if user has permission
     */
    public static function can(string $module, string $action): bool
    {
        // Super admin has all permissions
        if (self::hasRole('super_admin')) {
            return true;
        }

        $permissions = self::permissions();
        return isset($permissions[$module]) && in_array($action, $permissions[$module]);
    }

    /**
     * Check if user has permission (alias for can)
     */
    public static function hasPermission(string $module, string $action): bool
    {
        return self::can($module, $action);
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole(string $role): bool
    {
        return Session::get('user_role') === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        return in_array(Session::get('user_role'), $roles);
    }

    /**
     * Require authentication
     */
    public static function requireAuth(): void
    {
        if (self::guest()) {
            Session::flash('error', 'Please log in to continue.');
            redirect('login');
        }
    }

    /**
     * Require specific permission
     */
    public static function requirePermission(string $module, string $action): void
    {
        self::requireAuth();
        
        if (!self::can($module, $action)) {
            Session::flash('error', 'You do not have permission to perform this action.');
            redirect('dashboard');
        }
    }

    /**
     * Require specific role
     */
    public static function requireRole(string|array $roles): void
    {
        self::requireAuth();
        
        $roles = (array) $roles;
        if (!self::hasAnyRole($roles)) {
            Session::flash('error', 'Access denied.');
            redirect('dashboard');
        }
    }

    /**
     * Check if account is locked out
     */
    private static function isLockedOut(string $email): bool
    {
        $attempts = Database::fetch(
            "SELECT COUNT(*) as count, MAX(attempted_at) as last_attempt 
             FROM login_attempts 
             WHERE email = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)",
            [$email, LOGIN_LOCKOUT_TIME]
        );

        return ($attempts['count'] ?? 0) >= MAX_LOGIN_ATTEMPTS;
    }

    /**
     * Record failed login attempt
     */
    private static function recordFailedAttempt(string $email): void
    {
        Database::insert('login_attempts', [
            'email' => $email,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'attempted_at' => date(DATETIME_FORMAT)
        ]);
    }

    /**
     * Clear failed login attempts
     */
    private static function clearFailedAttempts(string $email): void
    {
        Database::delete('login_attempts', 'email = ?', [$email]);
    }

    /**
     * Create remember me token
     */
    private static function createRememberToken(int $userId): void
    {
        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);
        $expires = date(DATETIME_FORMAT, time() + (30 * 24 * 60 * 60)); // 30 days

        Database::update('users', [
            'remember_token' => $hashedToken,
            'remember_expires' => $expires
        ], 'id = ?', [$userId]);

        setcookie('remember_token', $token, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'secure' => SESSION_SECURE,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Validate remember me token
     */
    private static function validateRememberToken(string $token): bool
    {
        $hashedToken = hash('sha256', $token);
        
        $user = Database::fetch(
            "SELECT id FROM users 
             WHERE remember_token = ? 
             AND remember_expires > NOW() 
             AND is_active = 1",
            [$hashedToken]
        );

        if ($user) {
            Session::set('user_id', $user['id']);
            Session::regenerate();
            return true;
        }

        return false;
    }

    /**
     * Clear remember me token
     */
    private static function clearRememberToken(): void
    {
        if (self::check()) {
            Database::update('users', [
                'remember_token' => null,
                'remember_expires' => null
            ], 'id = ?', [self::id()]);
        }

        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => SESSION_SECURE,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Log user activity
     */
    public static function logActivity(int $userId, string $action, string $entityType, ?int $entityId = null, ?array $details = null): void
    {
        Database::insert('activity_logs', [
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'details' => $details ? json_encode($details) : null,
            'created_at' => date(DATETIME_FORMAT)
        ]);
    }

    /**
     * Hash a password
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_COST]);
    }

    /**
     * Verify password
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
