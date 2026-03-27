<!-- Asset Detail View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('assets') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Assets
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= e($asset['name'] ?? 'Asset') ?></h1>
        <p class="text-gray-500 mt-1"><?= e($asset['asset_tag'] ?? '') ?> · <?= e($asset['category_name'] ?? '') ?></p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <?php if (Auth::hasPermission('assets', 'print')): ?>
        <a href="<?= url('assets/print?id=' . $asset['id']) ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <?php endif; ?>
        <?php if (Auth::hasPermission('assets', 'edit')): ?>
        <a href="<?= url('assets/edit?id=' . $asset['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Overview Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Asset Details</h2>
                <?= statusBadge($asset['status'] ?? 'available') ?>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Category</p>
                    <p class="font-medium text-gray-900"><?= e($asset['category_name'] ?? '-') ?></p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Serial</p>
                    <p class="font-medium text-gray-900"><?= e($asset['serial_number'] ?? '—') ?></p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Location</p>
                    <p class="font-medium text-gray-900"><?= e($asset['location'] ?? '—') ?></p>
                </div>
                <div class="text-center p-3 bg-gold-50 rounded-lg">
                    <p class="text-sm text-gray-500">Value</p>
                    <p class="font-bold text-gold-500"><?= formatCurrency($asset['purchase_price'] ?? 0) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Purchase Date</p>
                    <p class="font-medium"><?= formatDate($asset['purchase_date'] ?? '') ?: '—' ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Warranty Expiry</p>
                    <p class="font-medium"><?= formatDate($asset['warranty_expiry'] ?? '') ?: '—' ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Assigned At</p>
                    <p class="font-medium"><?= !empty($asset['assigned_at']) ? formatDateTime($asset['assigned_at']) : '—' ?></p>
                </div>
            </div>

            <?php if (!empty($asset['description'])): ?>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-2">Description</h3>
                <p class="text-gray-600"><?= nl2br(e($asset['description'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($asset['notes'])): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 uppercase mb-3">Notes</h2>
            <p class="text-gray-600 whitespace-pre-line"><?= e($asset['notes']) ?></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Assignment -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">
                <i class="fas fa-user mr-2 text-gold-500"></i>Assignment
            </h3>
            <?php if (!empty($asset['employee_id'])): ?>
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-gold-500"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900"><?= e($asset['employee_name'] ?? Employee::getFullName($employee ?? [])) ?></p>
                    <a href="<?= url('hr/employees/view?id=' . $asset['employee_id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">View Employee</a>
                </div>
            </div>
            <?php if (Auth::hasPermission('assets', 'edit')): ?>
            <form action="<?= url('assets/assign') ?>" method="POST">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <input type="hidden" name="id" value="<?= $asset['id'] ?>">
                <input type="hidden" name="employee_id" value="">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-times mr-2"></i>Unassign
                </button>
            </form>
            <?php endif; ?>
            <?php else: ?>
            <p class="text-gray-500 text-sm mb-4">This asset is not assigned to any employee.</p>
            <?php if (Auth::hasPermission('assets', 'edit')): ?>
            <form action="<?= url('assets/assign') ?>" method="POST" class="space-y-3">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <input type="hidden" name="id" value="<?= $asset['id'] ?>">
                <select name="employee_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    <option value="">Select Employee</option>
                    <?php
                    $empList = Employee::dropdown();
                    foreach ($empList ?? [] as $eid => $ename):
                    ?>
                    <option value="<?= $eid ?>"><?= e($ename) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-user-plus mr-2"></i>Assign Asset
                </button>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if (Auth::hasPermission('assets', 'delete')): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <form action="<?= url('assets/delete') ?>" method="POST" onsubmit="return confirm('Archive this asset?');">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <input type="hidden" name="id" value="<?= $asset['id'] ?>">
                <button type="submit" class="w-full px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition text-sm">
                    <i class="fas fa-archive mr-2"></i>Archive Asset
                </button>
            </form>
        </div>
        <?php endif; ?>

        <div class="text-xs text-gray-400 space-y-1">
            <p>Created: <?= formatDateTime($asset['created_at'] ?? '') ?></p>
            <?php if (!empty($asset['updated_at'])): ?>
            <p>Updated: <?= formatDateTime($asset['updated_at']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
