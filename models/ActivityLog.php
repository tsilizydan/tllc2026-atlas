<?php
/**
 * TSILIZY CORE - Activity Log Model
 */

class ActivityLog extends Model
{
    protected static string $table = 'activity_logs';
    protected static array $fillable = [
        'user_id', 'action', 'description', 'ip_address', 'user_agent'
    ];

    /**
     * Log an activity
     */
    public static function log(string $action, string $description = ''): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    /**
     * Get recent logs with user info
     */
    public static function recent(int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT l.*, u.first_name, u.last_name, u.email 
             FROM activity_logs l 
             LEFT JOIN users u ON l.user_id = u.id 
             ORDER BY l.created_at DESC 
             LIMIT ?",
            [$limit]
        );
    }
}
