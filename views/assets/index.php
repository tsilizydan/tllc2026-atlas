<!-- Asset List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Assets</h1>
        <p class="text-gray-500 mt-1">Manage facilities and company assets</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <?php if (Auth::hasPermission('assets', 'print')): ?>
        <a href="<?= url('assets/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <?php endif; ?>
        <?php if (Auth::hasPermission('assets', 'create')): ?>
        <a href="<?= url('assets/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Asset
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Assets</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Available</p>
        <p class="text-2xl font-bold text-green-600"><?= $stats['available'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Assigned</p>
        <p class="text-2xl font-bold text-blue-600"><?= $stats['assigned'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">In Repair</p>
        <p class="text-2xl font-bold text-yellow-600"><?= $stats['in_repair'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Value</p>
        <p class="text-2xl font-bold text-gold-500"><?= formatCurrency($stats['total_value'] ?? 0) ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('assets') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?= e($search ?? '') ?>"
                placeholder="Search by name, tag, serial..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
        </div>
        <div>
            <select name="category" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Categories</option>
                <?php foreach ($categories ?? [] as $id => $name): ?>
                <option value="<?= $id ?>" <?= ($categoryId ?? '') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <select name="status" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="available" <?= ($status ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="assigned" <?= ($status ?? '') === 'assigned' ? 'selected' : '' ?>>Assigned</option>
                <option value="in_repair" <?= ($status ?? '') === 'in_repair' ? 'selected' : '' ?>>In Repair</option>
                <option value="retired" <?= ($status ?? '') === 'retired' ? 'selected' : '' ?>>Retired</option>
                <option value="lost" <?= ($status ?? '') === 'lost' ? 'selected' : '' ?>>Lost</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </form>
</div>

<!-- Assets Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($assets)): ?>
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Value</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($assets as $asset): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="<?= url('assets/view?id=' . $asset['id']) ?>" class="font-medium text-gray-900 hover:text-gold-500">
                            <?= e($asset['name']) ?>
                        </a>
                        <p class="text-sm text-gray-500"><?= e($asset['asset_tag']) ?><?= !empty($asset['serial_number']) ? ' · ' . e($asset['serial_number']) : '' ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas <?= e($asset['category_icon'] ?? 'fa-box') ?> mr-1"></i>
                            <?= e($asset['category_name'] ?? '-') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?= !empty($asset['employee_name']) ? e($asset['employee_name']) : '<span class="text-gray-400">—</span>' ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= statusBadge($asset['status'] ?? 'available') ?>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                        <?= formatCurrency($asset['purchase_price'] ?? 0) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('assets/view?id=' . $asset['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (Auth::hasPermission('assets', 'print')): ?>
                            <a href="<?= url('assets/print?id=' . $asset['id']) ?>" target="_blank" class="p-2 text-gray-400 hover:text-gray-600" title="Print">
                                <i class="fas fa-print"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (Auth::hasPermission('assets', 'edit')): ?>
                            <a href="<?= url('assets/edit?id=' . $asset['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (Auth::hasPermission('assets', 'delete')): ?>
                            <form action="<?= url('assets/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Archive this asset?');">
                                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                                <input type="hidden" name="id" value="<?= $asset['id'] ?>">
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600" title="Archive">
                                    <i class="fas fa-archive"></i>
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
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-box-open text-3xl"></i>
        </div>
        <h3 class="empty-title">No assets found</h3>
        <p class="empty-desc">Add your first asset to track equipment, devices, and facilities.</p>
        <?php if (Auth::hasPermission('assets', 'create')): ?>
        <a href="<?= url('assets/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
            <i class="fas fa-plus mr-2"></i>Add Asset
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
