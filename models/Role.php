<?php
/**
 * TSILIZY CORE - Role Model
 */

class Role extends Model
{
    protected static string $table = 'roles';
    protected static array $fillable = ['name', 'slug', 'description', 'permissions'];

    /**
     * Get role by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::where('slug = ?', [$slug])[0] ?? null;
    }

    /**
     * Get permissions for a role (decoded)
     */
    public static function getPermissions(int $roleId): array
    {
        $role = self::find($roleId);
        if (!$role || empty($role['permissions'])) {
            return [];
        }
        
        return json_decode($role['permissions'], true) ?? [];
    }
}
