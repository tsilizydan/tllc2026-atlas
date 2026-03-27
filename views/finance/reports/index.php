<!-- Financial Reports -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('finance') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Finance
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Financial Reports</h1>
        <p class="text-gray-500 mt-1">Generate and download financial reports</p>
    </div>
</div>

<!-- Date Range Filter -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('finance/reports') ?>" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    <option value="summary" <?= ($reportType ?? '') === 'summary' ? 'selected' : '' ?>>Summary</option>
                    <option value="profit_loss" <?= ($reportType ?? '') === 'profit_loss' ? 'selected' : '' ?>>Profit & Loss</option>
                    <option value="income" <?= ($reportType ?? '') === 'income' ? 'selected' : '' ?>>Income Only</option>
                    <option value="expenses" <?= ($reportType ?? '') === 'expenses' ? 'selected' : '' ?>>Expenses Only</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" name="from" value="<?= $fromDate ?? date('Y-m-01') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" name="to" value="<?= $toDate ?? date('Y-m-t') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
        </div>
        
        <div class="flex space-x-2">
            <button type="submit" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-chart-bar mr-2"></i>Generate
            </button>
            <a href="<?= url('finance/reports/export') ?>?<?= http_build_query($_GET) ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-download mr-2"></i>Export
            </a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Income</p>
                <p class="text-2xl font-bold text-green-600"><?= formatCurrency($report['total_income'] ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-up text-green-500"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Expenses</p>
                <p class="text-2xl font-bold text-red-600"><?= formatCurrency($report['total_expenses'] ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-down text-red-500"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Net Profit</p>
                <?php $netProfit = ($report['total_income'] ?? 0) - ($report['total_expenses'] ?? 0); ?>
                <p class="text-2xl font-bold <?= $netProfit >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                    <?= formatCurrency($netProfit) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-gold-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-gold-500"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Profit Margin</p>
                <?php 
                $margin = ($report['total_income'] ?? 0) > 0 
                    ? ($netProfit / $report['total_income']) * 100 
                    : 0;
                ?>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($margin, 1) ?>%</p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-percentage text-gray-500"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Income by Category -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Income by Category</h2>
        </div>
        
        <?php if (!empty($report['income_by_category'])): ?>
        <div class="p-5">
            <canvas id="incomeChart" height="250"></canvas>
        </div>
        
        <div class="divide-y divide-gray-100">
            <?php foreach ($report['income_by_category'] as $category => $amount): ?>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-gray-600"><?= ucfirst($category) ?></span>
                <span class="font-medium text-green-600"><?= formatCurrency($amount) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-chart-pie text-3xl mb-2 opacity-50"></i>
            <p>No income data for this period</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Expenses by Category -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Expenses by Category</h2>
        </div>
        
        <?php if (!empty($report['expenses_by_category'])): ?>
        <div class="p-5">
            <canvas id="expensesChart" height="250"></canvas>
        </div>
        
        <div class="divide-y divide-gray-100">
            <?php foreach ($report['expenses_by_category'] as $category => $amount): ?>
            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-gray-600"><?= ucfirst($category) ?></span>
                <span class="font-medium text-red-600"><?= formatCurrency($amount) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-chart-pie text-3xl mb-2 opacity-50"></i>
            <p>No expense data for this period</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Monthly Trend -->
<div class="bg-white rounded-xl border border-gray-200 mt-6">
    <div class="p-5 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Monthly Trend</h2>
    </div>
    
    <div class="p-5">
        <canvas id="trendChart" height="100"></canvas>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-xl border border-gray-200 mt-6">
    <div class="p-5 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Recent Transactions</h2>
    </div>
    
    <?php if (!empty($report['recent_transactions'])): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($report['recent_transactions'] as $tx): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-sm text-gray-600"><?= formatDate($tx['date']) ?></td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium <?= $tx['type'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <?= ucfirst($tx['type']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-900"><?= e($tx['description']) ?></td>
                    <td class="px-5 py-3 text-sm text-gray-600"><?= ucfirst($tx['category'] ?? '-') ?></td>
                    <td class="px-5 py-3 text-sm font-medium text-right <?= $tx['type'] === 'income' ? 'text-green-600' : 'text-red-600' ?>">
                        <?= $tx['type'] === 'income' ? '+' : '-' ?><?= formatCurrency($tx['amount']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="p-8 text-center text-gray-500">
        <i class="fas fa-receipt text-3xl mb-2 opacity-50"></i>
        <p>No transactions for this period</p>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Income Chart
<?php if (!empty($report['income_by_category'])): ?>
new Chart(document.getElementById('incomeChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_keys($report['income_by_category'])) ?>,
        datasets: [{
            data: <?= json_encode(array_values($report['income_by_category'])) ?>,
            backgroundColor: ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#d1fae5'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
<?php endif; ?>

// Expenses Chart
<?php if (!empty($report['expenses_by_category'])): ?>
new Chart(document.getElementById('expensesChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_keys($report['expenses_by_category'])) ?>,
        datasets: [{
            data: <?= json_encode(array_values($report['expenses_by_category'])) ?>,
            backgroundColor: ['#ef4444', '#f87171', '#fca5a5', '#fecaca', '#fee2e2'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
<?php endif; ?>

// Monthly Trend Chart
<?php if (!empty($report['monthly_trend'])): ?>
new Chart(document.getElementById('trendChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($report['monthly_trend'])) ?>,
        datasets: [
            {
                label: 'Income',
                data: <?= json_encode(array_column($report['monthly_trend'], 'income')) ?>,
                backgroundColor: '#10b981'
            },
            {
                label: 'Expenses',
                data: <?= json_encode(array_column($report['monthly_trend'], 'expenses')) ?>,
                backgroundColor: '#ef4444'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true } }
    }
});
<?php endif; ?>
</script>
