<!-- Contracts List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Contracts</h1>
        <p class="text-gray-500 mt-1">Manage your contracts and agreements</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('contracts/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <a href="<?= url('contracts/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>New Contract
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Contracts</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Active</p>
        <p class="text-2xl font-bold text-green-600"><?= $stats['active'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Expiring Soon</p>
        <p class="text-2xl font-bold text-yellow-600"><?= $stats['expiring_soon'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Value</p>
        <p class="text-2xl font-bold text-gold-500"><?= formatCurrency($stats['total_value'] ?? 0) ?></p>
    </div>
</div>

<!-- Expiring Soon Alert -->
<?php if (!empty($expiringContracts)): ?>
<div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
    <div class="flex">
        <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
        <div>
            <p class="font-medium text-yellow-800">Contracts Expiring Soon</p>
            <p class="text-sm text-yellow-700 mt-1">
                <?= count($expiringContracts) ?> contract(s) will expire within the next 30 days.
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('contracts') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search contracts..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="status" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="terminated" <?= ($status ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
            </select>
        </div>
        <div>
            <select name="type" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Types</option>
                <option value="service" <?= ($type ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                <option value="partnership" <?= ($type ?? '') === 'partnership' ? 'selected' : '' ?>>Partnership</option>
                <option value="nda" <?= ($type ?? '') === 'nda' ? 'selected' : '' ?>>NDA</option>
                <option value="employment" <?= ($type ?? '') === 'employment' ? 'selected' : '' ?>>Employment</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </form>
</div>

<!-- Contracts Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($contracts)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contract</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client/Partner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($contracts as $contract): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="<?= url('contracts/view?id=' . $contract['id']) ?>" class="font-medium text-gray-900 hover:text-gold-500">
                            <?= e($contract['contract_number']) ?>
                        </a>
                        <p class="text-sm text-gray-500 truncate max-w-xs"><?= e($contract['title']) ?></p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= e($contract['company_name'] ?? $contract['partner_name'] ?? 'N/A') ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                            <?= ucfirst($contract['type'] ?? 'service') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= formatDate($contract['start_date']) ?> - <?= formatDate($contract['end_date']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                        <?= formatCurrency($contract['value'] ?? 0) ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= statusBadge($contract['status']) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('contracts/view?id=' . $contract['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (!empty($contract['document'])): ?>
                            <a href="<?= upload($contract['document']) ?>" target="_blank" class="p-2 text-gray-400 hover:text-blue-600" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?= url('contracts/edit?id=' . $contract['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
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
            <i class="fas fa-file-contract text-3xl"></i>
        </div>
        <h3 class="empty-title">No contracts found</h3>
        <p class="empty-desc">Create your first contract to formalize agreements with clients and partners.</p>
        <a href="<?= url('contracts/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
            <i class="fas fa-plus mr-2"></i>Create Contract
        </a>
    </div>
    <?php endif; ?>
</div>
