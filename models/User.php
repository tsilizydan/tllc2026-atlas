<?php
/**
 * TSILIZY CORE - User Model
 */

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'username', 'email', 'password_hash', 'role_id',
        'first_name', 'last_name', 'avatar', 'is_active'
    ];

    /**
     * Get all users with role information
     */
    public static function allWithRoles(): array
    {
        return Database::fetchAll(
            "SELECT u.*, r.name as role_name, r.slug as role_slug 
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             ORDER BY u.id ASC"
        );
    }

    /**
     * Find user with role
     */
    public static function findWithRole(int $id): ?array
    {
        return Database::fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug, r.permissions
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ?",
            [$id]
        );
    }

    /**
     * Find by email
     */
    public static function findByEmail(string $email): ?array
    {
        return Database::fetch(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    /**
     * Find by username
     */
    public static function findByUsername(string $username): ?array
    {
        return Database::fetch(
            "SELECT * FROM users WHERE username = ?",
            [$username]
        );
    }

    /**
     * Create user with hashed password
     */
    public static function createUser(array $data): int
    {
        $data['password_hash'] = Auth::hashPassword($data['password']);
        unset($data['password'], $data['password_confirmation']);
        
        return self::create($data);
    }

    /**
     * Update user password
     */
    public static function updatePassword(int $id, string $password): bool
    {
        return Database::update('users', [
            'password_hash' => Auth::hashPassword($password),
            'updated_at' => date(DATETIME_FORMAT)
        ], 'id = ?', [$id]) > 0;
    }

    /**
     * Get full name
     */
    public static function getFullName(array $user): string
    {
        return $user['first_name'] . ' ' . $user['last_name'];
    }

    /**
     * Get active users count
     */
    public static function activeCount(): int
    {
        return Database::count('users', 'is_active = 1');
    }

    /**
     * Get users by role
     */
    public static function byRole(string $roleSlug): array
    {
        return Database::fetchAll(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             WHERE r.slug = ? AND u.is_active = 1 
             ORDER BY u.first_name ASC",
            [$roleSlug]
        );
    }
}
