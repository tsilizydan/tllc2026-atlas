<!-- Paychecks List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Paychecks</h1>
        <p class="text-gray-500 mt-1">Manage payroll and compensation</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('hr/paychecks/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Report
        </a>
        <a href="<?= url('hr/paychecks/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>New Paycheck
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Paychecks</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">This Month</p>
        <p class="text-2xl font-bold text-blue-600"><?= formatCurrency($stats['this_month'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Pending</p>
        <p class="text-2xl font-bold text-yellow-600"><?= formatCurrency($stats['pending'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Paid This Year</p>
        <p class="text-2xl font-bold text-green-600"><?= formatCurrency($stats['paid_year'] ?? 0) ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('hr/paychecks') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search by employee..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="month" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Months</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= ($month ?? '') == $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <select name="status" class="w-full sm:w-32 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </form>
</div>

<!-- Paychecks Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($paychecks)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Base Salary</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Bonuses</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Deductions</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net Pay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($paychecks as $paycheck): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gold-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-gold-500">
                                    <?= strtoupper(substr($paycheck['first_name'] ?? '', 0, 1) . substr($paycheck['last_name'] ?? '', 0, 1)) ?>
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?= e(($paycheck['first_name'] ?? '') . ' ' . ($paycheck['last_name'] ?? '')) ?></p>
                                <p class="text-xs text-gray-500"><?= e($paycheck['employee_code'] ?? '') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= formatDate($paycheck['pay_period_start']) ?> - <?= formatDate($paycheck['pay_period_end']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 text-right">
                        <?= formatCurrency($paycheck['base_salary']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-green-600 text-right">
                        +<?= formatCurrency($paycheck['bonuses']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-red-600 text-right">
                        -<?= formatCurrency($paycheck['deductions']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">
                        <?= formatCurrency($paycheck['net_pay']) ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= statusBadge($paycheck['status']) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('hr/paychecks/edit?id=' . ($paycheck['id'] ?? 0)) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= url('hr/paychecks/print?id=' . ($paycheck['id'] ?? 0)) ?>" target="_blank" class="p-2 text-gray-400 hover:text-gray-600" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            <?php if (($paycheck['status'] ?? '') === 'pending'): ?>
                            <form action="<?= url('hr/paychecks/process?id=' . ($paycheck['id'] ?? 0)) ?>" method="POST" class="inline">
                                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                                <button type="submit" class="p-2 text-gray-400 hover:text-green-600" title="Mark as Paid">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php include VIEWS_PATH . '/components/pagination.php'; ?>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-money-check-alt text-3xl"></i>
        </div>
        <h3 class="empty-title">No paychecks found</h3>
        <p class="empty-desc">Create paychecks to process payroll for your team.</p>
        <a href="<?= url('hr/paychecks/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
            <i class="fas fa-plus mr-2"></i>Create Paycheck
        </a>
    </div>
    <?php endif; ?>
</div>
