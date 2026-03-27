<!-- Employees List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Employees</h1>
        <p class="text-gray-500 mt-1">Manage your team and personnel</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('hr/print-directory') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Directory
        </a>
        <a href="<?= url('hr/employees/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-user-plus mr-2"></i>Add Employee
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Employees</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Active</p>
        <p class="text-2xl font-bold text-green-600"><?= $stats['active'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Departments</p>
        <p class="text-2xl font-bold text-blue-600"><?= $stats['departments'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">This Month Payroll</p>
        <p class="text-2xl font-bold text-gold-500"><?= formatCurrency($stats['monthly_payroll'] ?? 0) ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('hr/employees') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search employees..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="department" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Departments</option>
                <?php foreach ($departments ?? [] as $dept): ?>
                <option value="<?= e($dept) ?>" <?= ($department ?? '') === $dept ? 'selected' : '' ?>><?= e($dept) ?></option>
                <?php endforeach; ?>
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

<!-- Employees Grid -->
<?php if (!empty($employees)): ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($employees as $employee): ?>
    <div class="bg-white rounded-xl border border-gray-200 hover:border-gold-300 hover:shadow-md transition overflow-hidden">
        <div class="p-5">
            <div class="flex items-start space-x-4">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <?php if (!empty($employee['photo'])): ?>
                    <img src="<?= upload($employee['photo']) ?>" alt="<?= e($employee['first_name']) ?>" 
                        class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                    <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-gold-100 flex items-center justify-center border-2 border-gold-200">
                        <span class="text-xl font-bold text-gold-500">
                            <?= strtoupper(substr($employee['first_name'] ?? '', 0, 1) . substr($employee['last_name'] ?? '', 0, 1)) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <a href="<?= url('hr/employees/view?id=' . $employee['id']) ?>" class="font-semibold text-gray-900 hover:text-gold-500 block truncate">
                        <?= e(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?>
                    </a>
                    <p class="text-sm text-gray-500 truncate"><?= e($employee['position'] ?? 'No Position') ?></p>
                    <p class="text-xs text-gray-400"><?= e($employee['department'] ?? 'No Department') ?></p>
                    
                    <div class="mt-2">
                        <?= statusBadge($employee['status'] ?? 'active') ?>
                    </div>
                </div>
            </div>
            
            <!-- Contact -->
            <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-gray-500 space-y-1">
                <?php if (!empty($employee['email'])): ?>
                <p class="truncate">
                    <i class="fas fa-envelope text-gray-400 w-5"></i>
                    <?= e($employee['email']) ?>
                </p>
                <?php endif; ?>
                <?php if (!empty($employee['phone'])): ?>
                <p>
                    <i class="fas fa-phone text-gray-400 w-5"></i>
                    <?= e($employee['phone']) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Actions Footer -->
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400"><?= e($employee['employee_code'] ?? '') ?></span>
            <div class="flex items-center space-x-2">
                <a href="<?= url('hr/employees/view?id=' . $employee['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="<?= url('hr/employees/edit?id=' . $employee['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="<?= url('hr/paychecks/create?employee_id=' . $employee['id']) ?>" class="p-2 text-gray-400 hover:text-green-600" title="Add Paycheck">
                    <i class="fas fa-money-check-alt"></i>
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
        <i class="fas fa-users text-3xl"></i>
    </div>
    <h3 class="empty-title">No employees found</h3>
    <p class="empty-desc">Add your first team member to manage payroll and HR records.</p>
    <a href="<?= url('hr/employees/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
        <i class="fas fa-user-plus mr-2"></i>Add Employee
    </a>
</div>
<?php endif; ?>
