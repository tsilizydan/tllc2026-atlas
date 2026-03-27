<?php
/**
 * TSILIZY CORE - Task Model
 */

class Task extends Model
{
    protected static string $table = 'tasks';
    protected static array $fillable = [
        'project_id', 'title', 'description', 'status',
        'priority', 'assigned_to', 'due_date', 'created_by'
    ];

    /**
     * Get tasks with assignee info
     */
    public static function withAssignee(int $projectId): array
    {
        return Database::fetchAll(
            "SELECT t.*, CONCAT(u.first_name, ' ', u.last_name) as assignee_name 
             FROM tasks t 
             LEFT JOIN users u ON t.assigned_to = u.id 
             WHERE t.project_id = ? 
             ORDER BY t.priority DESC, t.due_date ASC",
            [$projectId]
        );
    }

    /**
     * Get tasks by status
     */
    public static function byStatus(int $projectId, string $status): array
    {
        return self::where('project_id = ? AND status = ?', [$projectId, $status]);
    }

    /**
     * Mark task as complete
     */
    public static function complete(int $id): bool
    {
        return self::update($id, [
            'status' => 'done',
            'completed_at' => date(DATETIME_FORMAT)
        ]);
    }

    /**
     * Get overdue tasks
     */
    public static function overdue(): array
    {
        return Database::fetchAll(
            "SELECT t.*, p.name as project_name, CONCAT(u.first_name, ' ', u.last_name) as assignee_name 
             FROM tasks t 
             LEFT JOIN projects p ON t.project_id = p.id 
             LEFT JOIN users u ON t.assigned_to = u.id 
             WHERE t.status != 'done' AND t.due_date < CURDATE() 
             ORDER BY t.due_date ASC"
        );
    }

    /**
     * Get user tasks
     */
    public static function forUser(int $userId): array
    {
        return Database::fetchAll(
            "SELECT t.*, p.name as project_name 
             FROM tasks t 
             LEFT JOIN projects p ON t.project_id = p.id 
             WHERE t.assigned_to = ? AND t.status != 'done' 
             ORDER BY t.priority DESC, t.due_date ASC",
            [$userId]
        );
    }

    /**
     * Get task statistics for project
     */
    public static function getProjectStats(int $projectId): array
    {
        return [
            'total' => self::count('project_id = ?', [$projectId]),
            'todo' => self::count("project_id = ? AND status = 'todo'", [$projectId]),
            'in_progress' => self::count("project_id = ? AND status = 'in_progress'", [$projectId]),
            'review' => self::count("project_id = ? AND status = 'review'", [$projectId]),
            'done' => self::count("project_id = ? AND status = 'done'", [$projectId])
        ];
    }
}
