<?php
/**
 * TSILIZY CORE - Project Model
 */

class Project extends Model
{
    protected static string $table = 'projects';
    protected static bool $softDelete = true;
    protected static array $fillable = [
        'client_id', 'name', 'description', 'status',
        'start_date', 'end_date', 'budget', 'created_by'
    ];

    /**
     * Get projects with client info
     */
    public static function allWithClient(bool $includeArchived = false): array
    {
        $where = $includeArchived ? '1=1' : 'p.is_archived = 0';
        
        return Database::fetchAll(
            "SELECT p.*, c.company_name as client_name 
             FROM projects p 
             LEFT JOIN clients c ON p.client_id = c.id 
             WHERE {$where} 
             ORDER BY p.created_at DESC"
        );
    }

    /**
     * Find project with full details
     */
    public static function findWithDetails(int $id): ?array
    {
        $project = Database::fetch(
            "SELECT p.*, c.company_name as client_name 
             FROM projects p 
             LEFT JOIN clients c ON p.client_id = c.id 
             WHERE p.id = ?",
            [$id]
        );
        
        if ($project) {
            $project['tasks'] = Task::where('project_id = ?', [$id], 'created_at DESC');
            $project['milestones'] = Milestone::where('project_id = ?', [$id], 'due_date ASC');
        }
        
        return $project;
    }

    /**
     * Get projects for dropdown
     */
    public static function dropdown(?int $clientId = null): array
    {
        $sql = "SELECT id, name FROM projects WHERE is_archived = 0";
        $params = [];
        
        if ($clientId) {
            $sql .= " AND client_id = ?";
            $params[] = $clientId;
        }
        
        $sql .= " ORDER BY name ASC";
        
        $rows = Database::fetchAll($sql, $params);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['id']] = $row['name'];
        }
        return $result;
    }

    /**
     * Get project statistics
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'planning' => self::count("status = 'planning'"),
            'active' => self::count("status = 'active'"),
            'on_hold' => self::count("status = 'on_hold'"),
            'completed' => self::count("status = 'completed'"),
            'total_budget' => (float) Database::fetchColumn(
                "SELECT COALESCE(SUM(budget), 0) FROM projects WHERE is_archived = 0"
            )
        ];
    }

    /**
     * Get task progress
     */
    public static function getProgress(int $projectId): array
    {
        $stats = Database::fetch(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as completed
             FROM tasks 
             WHERE project_id = ?",
            [$projectId]
        );
        
        $total = (int) ($stats['total'] ?? 0);
        $completed = (int) ($stats['completed'] ?? 0);
        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
        
        return [
            'total' => $total,
            'completed' => $completed,
            'percentage' => $percentage
        ];
    }

    /**
     * Auto-archive completed projects
     */
    public static function autoArchiveCompleted(): int
    {
        return Database::query(
            "UPDATE projects SET is_archived = 1, archived_at = NOW() 
             WHERE status = 'completed' AND is_archived = 0 
             AND updated_at < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        )->rowCount();
    }

    /**
     * Get recent projects
     */
    public static function recent(int $limit = 5): array
    {
        return Database::fetchAll(
            "SELECT p.*, c.company_name 
             FROM projects p 
             LEFT JOIN clients c ON p.client_id = c.id 
             WHERE p.is_archived = 0 
             ORDER BY p.created_at DESC 
             LIMIT ?",
            [$limit]
        );
    }
}
