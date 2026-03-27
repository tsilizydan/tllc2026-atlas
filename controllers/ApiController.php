<?php
/**
 * TSILIZY CORE - API Controller
 * Handles AJAX endpoints for dashboard charts and dynamic data
 */

class ApiController
{
    /**
     * Get dashboard statistics
     */
    public function dashboardStats(): void
    {
        Auth::requireAuth();

        $stats = [
            'invoices' => Invoice::getStats(),
            'projects' => Project::getStats(),
            'clients' => Client::getStats(),
            'employees' => Employee::getStats(),
            'contracts' => Contract::getStats(),
            'partners' => Partner::getStats()
        ];

        // Monthly financial summary
        $thisMonth = date('Y-m');
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        $stats['finance'] = [
            'monthly_income' => Income::getTotal($monthStart, $monthEnd),
            'monthly_expenses' => Expense::getTotal($monthStart, $monthEnd),
            'outstanding_invoices' => $stats['invoices']['outstanding'] ?? 0,
            'total_revenue' => $stats['invoices']['total_revenue'] ?? 0
        ];
        $stats['finance']['monthly_profit'] = $stats['finance']['monthly_income'] - $stats['finance']['monthly_expenses'];

        jsonResponse([
            'success' => true,
            'stats' => $stats,
            'timestamp' => date('c')
        ]);
    }

    /**
     * Get finance chart data
     */
    public function financeChart(): void
    {
        Auth::requireAuth();

        $year = (int) ($_GET['year'] ?? date('Y'));
        $type = $_GET['type'] ?? 'monthly';

        if ($type === 'monthly') {
            $income = Income::getMonthly($year);
            $expenses = Expense::getMonthly($year);

            // Prepare month labels and data
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $incomeData = array_fill(0, 12, 0);
            $expenseData = array_fill(0, 12, 0);

            foreach ($income as $row) {
                $month = (int) $row['month'] - 1;
                $incomeData[$month] = (float) $row['total'];
            }

            foreach ($expenses as $row) {
                $month = (int) $row['month'] - 1;
                $expenseData[$month] = (float) $row['total'];
            }

            $profitData = [];
            for ($i = 0; $i < 12; $i++) {
                $profitData[$i] = $incomeData[$i] - $expenseData[$i];
            }

            jsonResponse([
                'success' => true,
                'type' => 'monthly',
                'year' => $year,
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Income',
                        'data' => $incomeData,
                        'borderColor' => 'rgb(34, 197, 94)',
                        'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                        'fill' => true
                    ],
                    [
                        'label' => 'Expenses',
                        'data' => $expenseData,
                        'borderColor' => 'rgb(239, 68, 68)',
                        'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                        'fill' => true
                    ],
                    [
                        'label' => 'Profit',
                        'data' => $profitData,
                        'borderColor' => 'rgb(201, 162, 39)',
                        'backgroundColor' => 'rgba(201, 162, 39, 0.1)',
                        'fill' => true
                    ]
                ]
            ]);
        } else if ($type === 'category') {
            $startDate = $_GET['start_date'] ?? date('Y-01-01');
            $endDate = $_GET['end_date'] ?? date('Y-12-31');

            $incomeByCategory = Income::byCategory($startDate, $endDate);
            $expenseByCategory = Expense::byCategory($startDate, $endDate);

            jsonResponse([
                'success' => true,
                'type' => 'category',
                'income' => [
                    'labels' => array_column($incomeByCategory, 'category'),
                    'data' => array_map('floatval', array_column($incomeByCategory, 'total'))
                ],
                'expenses' => [
                    'labels' => array_column($expenseByCategory, 'category'),
                    'data' => array_map('floatval', array_column($expenseByCategory, 'total'))
                ]
            ]);
        }

        jsonResponse(['error' => 'Invalid chart type'], 400);
    }

    /**
     * Get projects chart data
     */
    public function projectsChart(): void
    {
        Auth::requireAuth();

        $type = $_GET['type'] ?? 'status';

        if ($type === 'status') {
            $stats = Project::getStats();

            jsonResponse([
                'success' => true,
                'type' => 'status',
                'labels' => ['Planning', 'Active', 'On Hold', 'Completed'],
                'data' => [
                    $stats['planning'] ?? 0,
                    $stats['active'] ?? 0,
                    $stats['on_hold'] ?? 0,
                    $stats['completed'] ?? 0
                ],
                'colors' => [
                    'rgb(156, 163, 175)', // gray for planning
                    'rgb(59, 130, 246)', // blue for active
                    'rgb(245, 158, 11)', // amber for on_hold
                    'rgb(34, 197, 94)'   // green for completed
                ]
            ]);
        } else if ($type === 'timeline') {
            // Get projects with timeline data
            $projects = Database::fetchAll(
                "SELECT id, name, status, start_date, end_date, 
                        DATEDIFF(IFNULL(end_date, CURDATE()), start_date) as duration
                 FROM projects 
                 WHERE is_archived = 0 AND start_date IS NOT NULL
                 ORDER BY start_date DESC
                 LIMIT 10"
            );

            jsonResponse([
                'success' => true,
                'type' => 'timeline',
                'projects' => $projects
            ]);
        } else if ($type === 'tasks') {
            // Get task distribution across projects
            $taskStats = Database::fetchAll(
                "SELECT p.name as project_name,
                        COUNT(*) as total_tasks,
                        SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) as completed_tasks
                 FROM projects p
                 LEFT JOIN tasks t ON p.id = t.project_id
                 WHERE p.is_archived = 0
                 GROUP BY p.id, p.name
                 HAVING total_tasks > 0
                 ORDER BY total_tasks DESC
                 LIMIT 10"
            );

            jsonResponse([
                'success' => true,
                'type' => 'tasks',
                'labels' => array_column($taskStats, 'project_name'),
                'datasets' => [
                    [
                        'label' => 'Completed',
                        'data' => array_map('intval', array_column($taskStats, 'completed_tasks')),
                        'backgroundColor' => 'rgb(34, 197, 94)'
                    ],
                    [
                        'label' => 'Total',
                        'data' => array_map('intval', array_column($taskStats, 'total_tasks')),
                        'backgroundColor' => 'rgb(201, 162, 39)'
                    ]
                ]
            ]);
        }

        jsonResponse(['error' => 'Invalid chart type'], 400);
    }

    /**
     * Search entities (autocomplete)
     */
    public function search(): void
    {
        Auth::requireAuth();

        $query = $_GET['q'] ?? '';
        $type = $_GET['type'] ?? 'all';

        if (strlen($query) < 2) {
            jsonResponse(['results' => []]);
        }

        $results = [];

        if ($type === 'all' || $type === 'clients') {
            $clients = Client::searchClients($query);
            foreach ($clients as $client) {
                $results[] = [
                    'type' => 'client',
                    'id' => $client['id'],
                    'title' => $client['company_name'],
                    'subtitle' => $client['contact_name'] ?? '',
                    'url' => url('clients/view', ['id' => $client['id']])
                ];
            }
        }

        if ($type === 'all' || $type === 'projects') {
            $projects = Project::search(['name', 'description'], $query);
            foreach ($projects as $project) {
                $results[] = [
                    'type' => 'project',
                    'id' => $project['id'],
                    'title' => $project['name'],
                    'subtitle' => $project['status'],
                    'url' => url('projects/view', ['id' => $project['id']])
                ];
            }
        }

        if ($type === 'all' || $type === 'invoices') {
            $invoices = Invoice::search(['invoice_number', 'notes'], $query);
            foreach ($invoices as $invoice) {
                $results[] = [
                    'type' => 'invoice',
                    'id' => $invoice['id'],
                    'title' => $invoice['invoice_number'],
                    'subtitle' => formatMoney($invoice['total']),
                    'url' => url('invoices/view', ['id' => $invoice['id']])
                ];
            }
        }

        jsonResponse([
            'success' => true,
            'results' => array_slice($results, 0, 20)
        ]);
    }

    /**
     * Get notifications (for header dropdown)
     */
    public function notifications(): void
    {
        Auth::requireAuth();

        $notifications = [];

        // Overdue invoices
        $overdueInvoices = Invoice::count("status = 'overdue'");
        if ($overdueInvoices > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'fa-file-invoice-dollar',
                'message' => "{$overdueInvoices} overdue invoice(s)",
                'url' => url('invoices', ['status' => 'overdue'])
            ];
        }

        // Expiring contracts
        $expiringContracts = count(Contract::expiringSoon(14));
        if ($expiringContracts > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'fa-file-contract',
                'message' => "{$expiringContracts} contract(s) expiring soon",
                'url' => url('contracts')
            ];
        }

        // Overdue tasks (if assigned to current user)
        $userId = Auth::id();
        $overdueTasks = Database::fetchColumn(
            "SELECT COUNT(*) FROM tasks WHERE assigned_to = ? AND status != 'done' AND due_date < CURDATE()",
            [$userId]
        );
        if ($overdueTasks > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'fa-tasks',
                'message' => "{$overdueTasks} overdue task(s)",
                'url' => url('dashboard')
            ];
        }

        jsonResponse([
            'success' => true,
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }
}
