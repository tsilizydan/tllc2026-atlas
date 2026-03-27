<!-- Clients List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Clients</h1>
        <p class="text-gray-500 mt-1">Manage your client relationships</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('clients/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <a href="<?= url('clients/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Client
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Clients</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Active</p>
        <p class="text-2xl font-bold text-green-600"><?= $stats['active'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Inactive</p>
        <p class="text-2xl font-bold text-gray-400"><?= $stats['inactive'] ?? 0 ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <form method="GET" action="<?= url('clients') ?>" class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search clients..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent"
            >
        </div>
        <div>
            <select name="status" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
        <?php if (!empty($search) || !empty($status)): ?>
        <a href="<?= url('clients') ?>" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition">
            <i class="fas fa-times mr-1"></i>Clear
        </a>
        <?php endif; ?>
    </form>
</div>

<!-- Clients Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($clients)): ?>
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($clients as $client): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="<?= url('clients/view?id=' . $client['id']) ?>" class="font-medium text-gray-900 hover:text-gold-500">
                            <?= e($client['company_name']) ?>
                        </a>
                        <?php if (!empty($client['website'])): ?>
                        <a href="<?= e($client['website']) ?>" target="_blank" class="block text-xs text-blue-500 hover:underline">
                            <i class="fas fa-external-link-alt mr-1"></i><?= e($client['website']) ?>
                        </a>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= e($client['contact_name'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php if (!empty($client['email'])): ?>
                        <a href="mailto:<?= e($client['email']) ?>" class="text-blue-500 hover:underline"><?= e($client['email']) ?></a>
                        <?php else: ?>-<?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= e($client['phone'] ?? '-') ?></td>
                    <td class="px-6 py-4">
                        <?= statusBadge($client['status'] ?? 'active') ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('clients/view?id=' . $client['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= url('clients/edit?id=' . $client['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= url('clients/delete?id=' . $client['id']) ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to archive this client?');">
                                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600" title="Archive">
                                    <i class="fas fa-archive"></i>
                                </button>
                            </form>
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
            <i class="fas fa-users text-3xl"></i>
        </div>
        <h3 class="empty-title">No clients found</h3>
        <p class="empty-desc">Get started by adding your first client. Clients help you manage contacts and invoices.</p>
        <a href="<?= url('clients/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
            <i class="fas fa-plus mr-2"></i>Add Client
        </a>
    </div>
    <?php endif; ?>
</div>
