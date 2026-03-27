<!-- Dashboard View -->

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Revenue -->
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    <?= formatCurrency($stats['finance']['total_revenue'] ?? 0) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center text-sm">
            <span class="text-green-600">
                <i class="fas fa-arrow-up mr-1"></i>
                <?= formatCurrency($stats['finance']['monthly_income'] ?? 0) ?>
            </span>
            <span class="text-gray-400 ml-2">this month</span>
        </div>
    </div>
    
    <!-- Outstanding Invoices -->
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Outstanding</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    <?= formatCurrency($stats['finance']['outstanding_invoices'] ?? 0) ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center text-sm">
            <span class="text-gray-600"><?= $stats['invoices']['sent'] ?? 0 ?> pending</span>
            <span class="text-red-500 ml-2"><?= $stats['invoices']['overdue'] ?? 0 ?> overdue</span>
        </div>
    </div>
    
    <!-- Active Projects -->
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Projects</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    <?= $stats['projects']['active'] ?? 0 ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-project-diagram text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center text-sm">
            <span class="text-gray-600"><?= $stats['projects']['total'] ?? 0 ?> total</span>
            <span class="text-green-500 ml-2"><?= $stats['projects']['completed'] ?? 0 ?> completed</span>
        </div>
    </div>
    
    <!-- Clients -->
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Clients</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    <?= $stats['clients']['active'] ?? 0 ?>
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-3 flex items-center text-sm">
            <span class="text-gray-600"><?= $stats['clients']['total'] ?? 0 ?> total</span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Invoices -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Invoices</h2>
            <a href="<?= url('invoices') ?>" class="text-sm text-gold-500 hover:text-gold-600">View All</a>
        </div>
        
        <?php if (!empty($recentInvoices)): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="pb-3">Invoice</th>
                        <th class="pb-3">Client</th>
                        <th class="pb-3">Amount</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($recentInvoices as $invoice): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">
                            <a href="<?= url('invoices/view?id=' . $invoice['id']) ?>" class="text-sm font-medium text-gray-900 hover:text-gold-500">
                                <?= e($invoice['invoice_number']) ?>
                            </a>
                        </td>
                        <td class="py-3">
                            <span class="text-sm text-gray-600"><?= e($invoice['company_name'] ?? 'N/A') ?></span>
                        </td>
                        <td class="py-3">
                            <span class="text-sm font-medium text-gray-900"><?= formatCurrency($invoice['total'] ?? 0) ?></span>
                        </td>
                        <td class="py-3">
                            <?= statusBadge($invoice['status']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-file-invoice text-4xl mb-3 opacity-50"></i>
            <p>No recent invoices</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Upcoming Milestones -->
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Upcoming Milestones</h2>
        </div>
        
        <?php if (!empty($upcomingMilestones)): ?>
        <div class="space-y-4">
            <?php foreach ($upcomingMilestones as $milestone): ?>
            <div class="flex items-start space-x-3 p-3 rounded-lg bg-gray-50">
                <div class="w-10 h-10 bg-gold-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-flag text-gold-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate"><?= e($milestone['title']) ?></p>
                    <p class="text-xs text-gray-500"><?= e($milestone['project_name']) ?></p>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="far fa-calendar mr-1"></i>
                        <?= formatDate($milestone['due_date']) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-flag text-4xl mb-3 opacity-50"></i>
            <p>No upcoming milestones</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Second Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Overdue Tasks -->
    <?php if (!empty($overdueTasks)): ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
        <div class="flex items-center space-x-2 mb-4">
            <i class="fas fa-exclamation-triangle text-red-500"></i>
            <h2 class="text-lg font-semibold text-red-800">Overdue Tasks</h2>
        </div>
        <div class="space-y-3">
            <?php foreach (array_slice($overdueTasks, 0, 5) as $task): ?>
            <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= e($task['title']) ?></p>
                    <p class="text-xs text-gray-500"><?= e($task['project_name'] ?? 'No Project') ?></p>
                </div>
                <span class="text-xs text-red-600 font-medium">
                    <?= timeAgo($task['due_date']) ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Expiring Contracts -->
    <?php if (!empty($expiringContracts)): ?>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
        <div class="flex items-center space-x-2 mb-4">
            <i class="fas fa-file-contract text-yellow-600"></i>
            <h2 class="text-lg font-semibold text-yellow-800">Contracts Expiring Soon</h2>
        </div>
        <div class="space-y-3">
            <?php foreach (array_slice($expiringContracts, 0, 5) as $contract): ?>
            <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900"><?= e($contract['title']) ?></p>
                    <p class="text-xs text-gray-500"><?= e($contract['client_name'] ?? 'No Client') ?></p>
                </div>
                <span class="text-xs text-yellow-700 font-medium">
                    Expires <?= formatDate($contract['end_date']) ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Recent Projects -->
<div class="bg-white rounded-xl border border-gray-200 p-5 mt-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
        <a href="<?= url('projects') ?>" class="text-sm text-gold-500 hover:text-gold-600">View All</a>
    </div>
    
    <?php if (!empty($recentProjects)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($recentProjects as $project): ?>
        <a href="<?= url('projects/view?id=' . $project['id']) ?>" class="block p-4 border border-gray-200 rounded-lg hover:border-gold-300 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-medium text-gray-900"><?= e($project['name']) ?></h3>
                    <p class="text-sm text-gray-500 mt-1"><?= e($project['company_name'] ?? 'Internal') ?></p>
                </div>
                <?= statusBadge($project['status']) ?>
            </div>
            <?php if (!empty($project['budget'])): ?>
            <div class="mt-3 text-sm text-gray-600">
                Budget: <span class="font-medium"><?= formatCurrency($project['budget'] ?? 0) ?></span>
            </div>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-project-diagram text-4xl mb-3 opacity-50"></i>
        <p>No recent projects</p>
    </div>
    <?php endif; ?>
</div>
