<!-- Income List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('finance') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Finance
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Income</h1>
        <p class="text-gray-500 mt-1">Track all revenue and income sources</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="<?= url('finance/income/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Income
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('finance/income') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search income..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="category" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Categories</option>
                <option value="invoice" <?= ($category ?? '') === 'invoice' ? 'selected' : '' ?>>Invoice Payment</option>
                <option value="service" <?= ($category ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                <option value="retainer" <?= ($category ?? '') === 'retainer' ? 'selected' : '' ?>>Retainer</option>
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

<!-- Income Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($incomes)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($incomes as $income): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= formatDate($income['date']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?= e($income['description']) ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">
                            <?= ucfirst($income['category'] ?? 'other') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= e($income['company_name'] ?? '-') ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600 text-right">
                        +<?= formatCurrency($income['amount']) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('finance/income/edit?id=' . $income['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= url('finance/income/delete?id=' . $income['id']) ?>" method="POST" class="inline"
                                onsubmit="return confirm('Delete this income entry?')">
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
                    <td class="px-6 py-4 text-lg font-bold text-green-600 text-right">
                        +<?= formatCurrency($totalIncome ?? 0) ?>
                    </td>
                    <td></td>
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
        <i class="fas fa-coins text-gray-300 text-5xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No income recorded</h3>
        <p class="text-gray-500 mb-4">Start tracking your revenue.</p>
        <a href="<?= url('finance/income/create') ?>" class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Income
        </a>
    </div>
    <?php endif; ?>
</div>
