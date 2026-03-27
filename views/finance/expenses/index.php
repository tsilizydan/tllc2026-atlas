<!-- Expenses List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('finance') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Finance
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Expenses</h1>
        <p class="text-gray-500 mt-1">Track all business expenses and costs</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="<?= url('finance/expenses/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Expense
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('finance/expenses') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search expenses..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="category" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Categories</option>
                <option value="payroll" <?= ($category ?? '') === 'payroll' ? 'selected' : '' ?>>Payroll</option>
                <option value="software" <?= ($category ?? '') === 'software' ? 'selected' : '' ?>>Software</option>
                <option value="marketing" <?= ($category ?? '') === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                <option value="office" <?= ($category ?? '') === 'office' ? 'selected' : '' ?>>Office</option>
                <option value="travel" <?= ($category ?? '') === 'travel' ? 'selected' : '' ?>>Travel</option>
                <option value="utilities" <?= ($category ?? '') === 'utilities' ? 'selected' : '' ?>>Utilities</option>
                <option value="taxes" <?= ($category ?? '') === 'taxes' ? 'selected' : '' ?>>Taxes</option>
                <option value="other" <?= ($category ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div>
            <input type="month" name="month" value="<?= $month ?? '' ?>"
                class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </form>
</div>

<!-- Expenses Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($expenses)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Receipt</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($expenses as $expense): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= formatDate($expense['date']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= e($expense['description']) ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">
                            <?= ucfirst($expense['category'] ?? 'other') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= e($expense['vendor'] ?? '-') ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-red-600 text-right">
                        -<?= formatCurrency($expense['amount']) ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if (!empty($expense['receipt'])): ?>
                        <a href="<?= upload($expense['receipt']) ?>" target="_blank" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-receipt"></i>
                        </a>
                        <?php else: ?>
                        <span class="text-gray-300"><i class="fas fa-receipt"></i></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('finance/expenses/edit?id=' . $expense['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= url('finance/expenses/delete?id=' . $expense['id']) ?>" method="POST" class="inline"
                                onsubmit="return confirm('Delete this expense entry?')">
                                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                <tr>
                    <td colspan="4" class="px-6 py-4 text-sm font-semibold text-gray-700">Total</td>
                    <td class="px-6 py-4 text-lg font-bold text-red-600 text-right">
                        -<?= formatCurrency($totalExpenses ?? 0) ?>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php include VIEWS_PATH . '/components/pagination.php'; ?>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="text-center py-12">
        <i class="fas fa-receipt text-gray-300 text-5xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No expenses recorded</h3>
        <p class="text-gray-500 mb-4">Start tracking your business expenses.</p>
        <a href="<?= url('finance/expenses/create') ?>" class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Expense
        </a>
    </div>
    <?php endif; ?>
</div>
