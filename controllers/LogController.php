<?php
/**
 * TSILIZY CORE - Activity Log Controller
 * Manages activity log viewing for admin users
 */

class LogController
{
    /**
     * Display activity logs
     */
    public function index(): void
    {
        Auth::requireAuth();
        Auth::requirePermission('logs', 'view');

        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 25;
        $filter = $_GET['filter'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';

        // Build where clause
        $where = '1=1';
        $params = [];

        if ($filter) {
            $where .= " AND (al.action LIKE ? OR al.entity_type LIKE ?)";
            $params[] = "%{$filter}%";
            $params[] = "%{$filter}%";
        }

        if ($dateFrom) {
            $where .= " AND DATE(al.created_at) >= ?";
            $params[] = $dateFrom;
        }

        if ($dateTo) {
            $where .= " AND DATE(al.created_at) <= ?";
            $params[] = $dateTo;
        }

        // Get total count
        $totalSql = "SELECT COUNT(*) FROM activity_logs al WHERE {$where}";
        $total = (int) Database::fetchColumn($totalSql, $params);

        // Get pagination
        $pagination = paginate($total, $page, $perPage);

        // Get logs with user info
        $sql = "SELECT al.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE {$where}
                ORDER BY al.created_at DESC
                LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}";

        $logs = Database::fetchAll($sql, $params);

        // Get unique actions for filter dropdown
        $actions = Database::fetchAll("SELECT DISTINCT action FROM activity_logs ORDER BY action ASC");
        $entityTypes = Database::fetchAll("SELECT DISTINCT entity_type FROM activity_logs ORDER BY entity_type ASC");

        $data = [
            'pageTitle' => 'Activity Logs',
            'logs' => $logs,
            'pagination' => $pagination,
            'filter' => $filter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'actions' => array_column($actions, 'action'),
            'entityTypes' => array_column($entityTypes, 'entity_type')
        ];

        view('logs/index', $data);
    }

    /**
     * View log details (AJAX)
     */
    public function view(): void
    {
        Auth::requireAuth();
        Auth::requirePermission('logs', 'view');

        $id = (int) ($_GET['id'] ?? 0);

        $log = Database::fetch(
            "SELECT al.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email
             FROM activity_logs al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE al.id = ?",
            [$id]
        );

        if (!$log) {
            jsonResponse(['error' => 'Log not found'], 404);
        }

        jsonResponse(['log' => $log]);
    }

    /**
     * Clear old logs (admin only)
     */
    public function clear(): void
    {
        Auth::requireAuth();
        Auth::requireRole('super_admin');

        if (!isPost()) {
            redirect('logs');
        }

        Session::validateCsrf();

        $olderThan = $_POST['older_than'] ?? '90'; // days
        $days = max(30, (int) $olderThan);

        $deleted = Database::query(
            "DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)",
            [$days]
        )->rowCount();

        Auth::logActivity(
            Auth::id(),
            'clear_logs',
            'activity_logs',
            null,
            ['deleted_count' => $deleted, 'older_than_days' => $days]
        );

        Session::flash('success', "Deleted {$deleted} log entries older than {$days} days.");
        redirect('logs');
    }

    /**
     * Export logs to CSV
     */
    public function export(): void
    {
        Auth::requireAuth();
        Auth::requirePermission('logs', 'view');

        $dateFrom = $_GET['date_from'] ?? date('Y-m-01');
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');

        $logs = Database::fetchAll(
            "SELECT al.*, CONCAT(u.first_name, ' ', u.last_name) as user_name
             FROM activity_logs al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE DATE(al.created_at) BETWEEN ? AND ?
             ORDER BY al.created_at DESC",
            [$dateFrom, $dateTo]
        );

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, ['ID', 'User', 'Action', 'Entity Type', 'Entity ID', 'IP Address', 'Created At']);

        // Data rows
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['user_name'] ?? 'System',
                $log['action'],
                $log['entity_type'],
                $log['entity_id'],
                $log['ip_address'],
                $log['created_at']
            ]);
        }

        fclose($output);
        exit;
    }
}
