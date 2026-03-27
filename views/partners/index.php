<!-- Partners List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Partners</h1>
        <p class="text-gray-500 mt-1">Manage business partners and affiliates</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('partners/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <a href="<?= url('partners/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Partner
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Partners</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Active</p>
        <p class="text-2xl font-bold text-green-600"><?= $stats['active'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Strategic</p>
        <p class="text-2xl font-bold text-gold-500"><?= $stats['strategic'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Active Contracts</p>
        <p class="text-2xl font-bold text-blue-600"><?= $stats['active_contracts'] ?? 0 ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('partners') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search partners..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="type" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Types</option>
                <option value="strategic" <?= ($type ?? '') === 'strategic' ? 'selected' : '' ?>>Strategic</option>
                <option value="affiliate" <?= ($type ?? '') === 'affiliate' ? 'selected' : '' ?>>Affiliate</option>
                <option value="vendor" <?= ($type ?? '') === 'vendor' ? 'selected' : '' ?>>Vendor</option>
                <option value="referral" <?= ($type ?? '') === 'referral' ? 'selected' : '' ?>>Referral</option>
            </select>
        </div>
        <div>
            <select name="status" class="w-full sm:w-32 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </form>
</div>

<!-- Partners Grid -->
<?php if (!empty($partners)): ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($partners as $partner): ?>
    <div class="bg-white rounded-xl border border-gray-200 hover:border-gold-300 hover:shadow-md transition overflow-hidden">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-3">
                    <?php if (!empty($partner['logo'])): ?>
                    <img src="<?= upload($partner['logo'] ?? '', 'logo') ?>" alt="<?= e($partner['company_name']) ?>" class="w-12 h-12 rounded-lg object-cover">
                    <?php else: ?>
                    <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-handshake text-gold-500"></i>
                    </div>
                    <?php endif; ?>
                    <div>
                        <a href="<?= url('partners/view?id=' . $partner['id']) ?>" class="font-semibold text-gray-900 hover:text-gold-500 block">
                            <?= e($partner['company_name']) ?>
                        </a>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                            <?= ucfirst($partner['type'] ?? 'partner') ?>
                        </span>
                    </div>
                </div>
                <?= statusBadge($partner['status'] ?? 'active') ?>
            </div>
            
            <?php if (!empty($partner['description'])): ?>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2"><?= e(truncate($partner['description'], 100)) ?></p>
            <?php endif; ?>
            
            <!-- Contact Info -->
            <div class="space-y-1 text-sm text-gray-500">
                <?php if (!empty($partner['contact_name'])): ?>
                <p>
                    <i class="fas fa-user text-gray-400 w-5"></i>
                    <?= e($partner['contact_name']) ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($partner['email'])): ?>
                <p class="truncate">
                    <i class="fas fa-envelope text-gray-400 w-5"></i>
                    <?= e($partner['email']) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Actions Footer -->
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400">
                <?= $partner['contract_count'] ?? 0 ?> contract(s)
            </span>
            <div class="flex items-center space-x-2">
                <a href="<?= url('partners/view?id=' . $partner['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="<?= url('partners/edit?id=' . $partner['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
<div class="mt-6">
    <?php include VIEWS_PATH . '/components/pagination.php'; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div class="empty-state bg-white rounded-xl border border-gray-200">
    <div class="empty-icon">
        <i class="fas fa-handshake text-3xl"></i>
    </div>
    <h3 class="empty-title">No partners found</h3>
    <p class="empty-desc">Add partners to build your business network and track collaborations.</p>
    <a href="<?= url('partners/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
        <i class="fas fa-plus mr-2"></i>Add Partner
    </a>
</div>
<?php endif; ?>
