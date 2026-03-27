<?php
/**
 * TSILIZY CORE - Milestone Model
 */

class Milestone extends Model
{
    protected static string $table = 'milestones';
    protected static array $fillable = [
        'project_id', 'title', 'description', 'due_date', 'status'
    ];

    /**
     * Mark milestone as achieved
     */
    public static function achieve(int $id): bool
    {
        return self::update($id, [
            'status' => 'achieved',
            'achieved_at' => date(DATETIME_FORMAT)
        ]);
    }

    /**
     * Get upcoming milestones
     */
    public static function upcoming(int $limit = 5): array
    {
        return Database::fetchAll(
            "SELECT m.*, p.name as project_name 
             FROM milestones m 
             LEFT JOIN projects p ON m.project_id = p.id 
             WHERE m.status = 'pending' AND m.due_date >= CURDATE() 
             ORDER BY m.due_date ASC 
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Get overdue milestones
     */
    public static function overdue(): array
    {
        return Database::fetchAll(
            "SELECT m.*, p.name as project_name 
             FROM milestones m 
             LEFT JOIN projects p ON m.project_id = p.id 
             WHERE m.status = 'pending' AND m.due_date < CURDATE() 
             ORDER BY m.due_date ASC"
        );
    }

    /**
     * Get milestone progress for project
     */
    public static function getProjectProgress(int $projectId): array
    {
        $stats = Database::fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'achieved' THEN 1 ELSE 0 END) as achieved
             FROM milestones 
             WHERE project_id = ?",
            [$projectId]
        );
        
        $total = (int) ($stats['total'] ?? 0);
        $achieved = (int) ($stats['achieved'] ?? 0);
        
        return [
            'total' => $total,
            'achieved' => $achieved,
            'pending' => $total - $achieved,
            'percentage' => $total > 0 ? round(($achieved / $total) * 100) : 0
        ];
    }
}
