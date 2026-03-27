<?php
/**
 * TSILIZY CORE - Dashboard Controller
 */

class DashboardController
{
    /**
     * Show dashboard
     */
    public function index(): void
    {
        Auth::requireAuth();

        try {
            $recentInvoices = Invoice::recent(5);
            $recentProjects = Project::recent(5);
            $upcomingMilestones = Milestone::upcoming(5);
            $overdueTasks = Task::overdue();
            $expiringContracts = Contract::expiringSoon(30);
        } catch (Throwable $e) {
            error_log('Dashboard index: ' . $e->getMessage());
            $recentInvoices = $recentProjects = $upcomingMilestones = $overdueTasks = $expiringContracts = [];
        }

        $data = [
            'pageTitle' => 'Dashboard',
            'stats' => $this->getStats(),
            'recentInvoices' => is_array($recentInvoices) ? $recentInvoices : [],
            'recentProjects' => is_array($recentProjects) ? $recentProjects : [],
            'upcomingMilestones' => is_array($upcomingMilestones) ? $upcomingMilestones : [],
            'overdueTasks' => is_array($overdueTasks) ? $overdueTasks : [],
            'expiringContracts' => is_array($expiringContracts) ? $expiringContracts : []
        ];

        view('dashboard/index', $data);
    }

    /**
     * Get dashboard statistics (safe defaults on model/DB errors)
     */
    private function getStats(): array
    {
        $defaultFinance = [
            'monthly_income' => 0.0, 'monthly_expenses' => 0.0, 'monthly_profit' => 0.0,
            'yearly_income' => 0.0, 'yearly_expenses' => 0.0, 'yearly_profit' => 0.0,
            'outstanding_invoices' => 0.0, 'total_revenue' => 0.0
        ];
        $defaultCounts = ['total' => 0, 'active' => 0, 'inactive' => 0, 'sent' => 0, 'overdue' => 0, 'draft' => 0, 'paid' => 0, 'completed' => 0, 'planning' => 0, 'on_hold' => 0];

        try {
            $monthStart = date('Y-m-01');
            $monthEnd = date('Y-m-t');
            $yearStart = date('Y-01-01');
            $yearEnd = date('Y-12-31');

            $monthlyIncome = Income::getTotal($monthStart, $monthEnd);
            $monthlyExpenses = Expense::getTotal($monthStart, $monthEnd);
            $yearlyIncome = Income::getTotal($yearStart, $yearEnd);
            $yearlyExpenses = Expense::getTotal($yearStart, $yearEnd);

            $invoiceStats = Invoice::getStats();
            $projectStats = Project::getStats();
            $clientStats = Client::getStats();
            $employeeStats = Employee::getStats();
            $contractStats = Contract::getStats();
            $partnerStats = Partner::getStats();
        } catch (Throwable $e) {
            error_log('Dashboard getStats: ' . $e->getMessage());
            $invoiceStats = $defaultCounts + ['total_revenue' => 0.0, 'outstanding' => 0.0];
            $projectStats = $clientStats = $employeeStats = $contractStats = $partnerStats = $defaultCounts;
            $monthlyIncome = $monthlyExpenses = $yearlyIncome = $yearlyExpenses = 0.0;
        }

        $invoiceStats = is_array($invoiceStats) ? $invoiceStats : $defaultCounts;
        $projectStats = is_array($projectStats) ? $projectStats : $defaultCounts;
        $clientStats = is_array($clientStats) ? $clientStats : $defaultCounts;
        $employeeStats = is_array($employeeStats) ? $employeeStats : $defaultCounts;
        $contractStats = is_array($contractStats) ? $contractStats : $defaultCounts;
        $partnerStats = is_array($partnerStats) ? $partnerStats : $defaultCounts;

        return [
            'finance' => [
                'monthly_income' => (float) ($monthlyIncome ?? 0),
                'monthly_expenses' => (float) ($monthlyExpenses ?? 0),
                'monthly_profit' => (float) (($monthlyIncome ?? 0) - ($monthlyExpenses ?? 0)),
                'yearly_income' => (float) ($yearlyIncome ?? 0),
                'yearly_expenses' => (float) ($yearlyExpenses ?? 0),
                'yearly_profit' => (float) (($yearlyIncome ?? 0) - ($yearlyExpenses ?? 0)),
                'outstanding_invoices' => (float) ($invoiceStats['outstanding'] ?? 0),
                'total_revenue' => (float) ($invoiceStats['total_revenue'] ?? 0)
            ],
            'invoices' => $invoiceStats + $defaultCounts,
            'projects' => $projectStats + $defaultCounts,
            'clients' => $clientStats + $defaultCounts,
            'employees' => $employeeStats + $defaultCounts,
            'contracts' => $contractStats + $defaultCounts,
            'partners' => $partnerStats + $defaultCounts
        ];
    }
}
