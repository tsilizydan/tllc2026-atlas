<!-- Finance Dashboard -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Finance Dashboard</h1>
        <p class="text-gray-500 mt-1">Track income, expenses, and financial health</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('finance/reports') ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-chart-line mr-2"></i>Reports
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Income</p>
                <p class="text-2xl font-bold text-green-600"><?= formatCurrency($totalIncome ?? $stats['total_income'] ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-down text-green-600 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">This year</p>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Expenses</p>
                <p class="text-2xl font-bold text-red-600"><?= formatCurrency($stats['total_expenses'] ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-up text-red-600 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">This year</p>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Net Profit</p>
                <?php $netProfit = ($totalIncome ?? 0) - ($totalExpenses ?? 0); ?>
                <p class="text-2xl font-bold <?= $netProfit >= 0 ? 'text-gold-500' : 'text-red-600' ?>">
                    <?= formatCurrency($netProfit) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-pie text-gold-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">This year</p>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Bank Balance</p>
                <p class="text-2xl font-bold text-blue-600"><?= formatCurrency($totalBalance ?? $stats['bank_balance'] ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-university text-blue-600 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2">All accounts</p>
    </div>
</div>

<!-- Charts & Quick Actions Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Monthly Trend Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend</h2>
        <div style="height: 300px; max-height: 300px; position: relative;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="space-y-3">
            <a href="<?= url('finance/income/create') ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition border border-gray-200">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-plus text-green-600"></i>
                </div>
                <span class="font-medium text-gray-700">Record Income</span>
            </a>
            <a href="<?= url('finance/expenses/create') ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition border border-gray-200">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-minus text-red-600"></i>
                </div>
                <span class="font-medium text-gray-700">Record Expense</span>
            </a>
            <a href="<?= url('finance/bank-accounts') ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition border border-gray-200">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-university text-blue-600"></i>
                </div>
                <span class="font-medium text-gray-700">Bank Accounts</span>
            </a>
            <a href="<?= url('finance/reports/generate') ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition border border-gray-200">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-alt text-purple-600"></i>
                </div>
                <span class="font-medium text-gray-700">Generate Report</span>
            </a>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Income -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Income</h2>
            <a href="<?= url('finance/income') ?>" class="text-sm text-gold-500 hover:text-gold-600">View all</a>
        </div>
        <?php $recentIncome = $recentIncome ?? []; ?>
        <?php if (!empty($recentIncome)): ?>
        <div class="divide-y divide-gray-100">
            <?php foreach (array_slice($recentIncome, 0, 5) as $income): ?>
            <?php $income = is_array($income) ? $income : []; ?>
            <div class="p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900"><?= e($income['description'] ?? '') ?></p>
                    <p class="text-xs text-gray-500"><?= formatDate($income['date'] ?? '') ?></p>
                </div>
                <span class="font-semibold text-green-600">+<?= formatCurrency($income['amount'] ?? 0) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
            <p>No recent income</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Recent Expenses -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Expenses</h2>
            <a href="<?= url('finance/expenses') ?>" class="text-sm text-gold-500 hover:text-gold-600">View all</a>
        </div>
        <?php $recentExpenses = $recentExpenses ?? []; ?>
        <?php if (!empty($recentExpenses)): ?>
        <div class="divide-y divide-gray-100">
            <?php foreach (array_slice($recentExpenses, 0, 5) as $expense): ?>
            <?php $expense = is_array($expense) ? $expense : []; ?>
            <div class="p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900"><?= e($expense['description'] ?? '') ?></p>
                    <p class="text-xs text-gray-500">
                        <?= formatDate($expense['date'] ?? '') ?>
                        <?php if (!empty($expense['category'])): ?>
                        • <?= e($expense['category']) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <span class="font-semibold text-red-600">-<?= formatCurrency($expense['amount'] ?? 0) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
            <p>No recent expenses</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chart Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    const chartData = <?= json_encode($chartData ?? ['labels' => [], 'income' => [], 'expenses' => []]) ?>;
    const labels = Array.isArray(chartData.labels) ? chartData.labels : [];
    const incomeData = Array.isArray(chartData.income) ? chartData.income : [];
    const expenseData = Array.isArray(chartData.expenses) ? chartData.expenses : [];
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Expenses',
                    data: expenseData,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
