<!-- Invoices List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
        <p class="text-gray-500 mt-1">Manage your invoices and payments</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('invoices/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <a href="<?= url('invoices/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>New Invoice
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Invoices</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Paid</p>
        <p class="text-2xl font-bold text-green-600"><?= formatCurrency($stats['total_paid'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Pending</p>
        <p class="text-2xl font-bold text-blue-600"><?= formatCurrency($stats['total_pending'] ?? 0) ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Overdue</p>
        <p class="text-2xl font-bold text-red-600"><?= formatCurrency($stats['total_overdue'] ?? 0) ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <form method="GET" action="<?= url('invoices') ?>" class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search invoices..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="status" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="sent" <?= ($status ?? '') === 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="paid" <?= ($status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="overdue" <?= ($status ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </form>
</div>

<!-- Invoices Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($invoices)): ?>
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($invoices as $invoice): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="<?= url('invoices/view?id=' . $invoice['id']) ?>" class="font-medium text-gray-900 hover:text-gold-500">
                            <?= e($invoice['invoice_number']) ?>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= e($invoice['company_name'] ?? 'Walk-in Client') ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= formatDate($invoice['issue_date']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= formatDate($invoice['due_date']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                        <?= formatCurrency($invoice['total'], $invoice['currency'] ?? 'USD') ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= statusBadge($invoice['status']) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('invoices/view?id=' . $invoice['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= url('invoices/print?id=' . $invoice['id']) ?>" target="_blank" class="p-2 text-gray-400 hover:text-gray-600" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="<?= url('invoices/edit?id=' . $invoice['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
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
            <i class="fas fa-file-invoice-dollar text-3xl"></i>
        </div>
        <h3 class="empty-title">No invoices found</h3>
        <p class="empty-desc">Create your first invoice to start tracking payments and billing clients.</p>
        <a href="<?= url('invoices/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
            <i class="fas fa-plus mr-2"></i>Create Invoice
        </a>
    </div>
    <?php endif; ?>
</div>
