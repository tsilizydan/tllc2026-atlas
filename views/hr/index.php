<!-- HR Dashboard -->

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">HR Dashboard</h1>
            <p class="text-gray-500 mt-1">Human Resources Overview</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['total'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Employees</p>
                    <p class="text-2xl font-bold text-green-600 mt-1"><?= $stats['active'] ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Monthly Salary</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatCurrency($stats['total_salary'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-gold-500 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Paychecks</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1"><?= is_array($pendingPaychecks) ? count($pendingPaychecks) : ($pendingPaychecks ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-orange-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Employees Section -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-users mr-2 text-gold-500"></i>Employees
                </h2>
                <a href="<?= url('hr/employees/create') ?>" class="text-gold-500 hover:text-gold-600">
                    <i class="fas fa-plus mr-1"></i>Add New
                </a>
            </div>
            
            <div class="space-y-3">
                <?php if (!empty($recentEmployees)): ?>
                    <?php foreach ($recentEmployees as $emp): ?>
                    <a href="<?= url('hr/employees/view?id=' . $emp['id']) ?>" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-white font-medium">
                            <?= strtoupper(substr($emp['first_name'] ?? '', 0, 1) . substr($emp['last_name'] ?? '', 0, 1)) ?>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-900"><?= e(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')) ?></p>
                            <p class="text-sm text-gray-500"><?= e($emp['position'] ?? 'N/A') ?></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No employees yet</p>
                <?php endif; ?>
            </div>
            
            <a href="<?= url('hr/employees') ?>" class="block text-center mt-4 text-sm text-gold-500 hover:text-gold-600">
                View All Employees →
            </a>
        </div>
        
        <!-- Paychecks Section -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-file-invoice-dollar mr-2 text-gold-500"></i>Recent Paychecks
                </h2>
                <a href="<?= url('hr/paychecks/create') ?>" class="text-gold-500 hover:text-gold-600">
                    <i class="fas fa-plus mr-1"></i>Create
                </a>
            </div>
            
            <div class="space-y-3">
                <?php if (!empty($recentPaychecks)): ?>
                    <?php foreach ($recentPaychecks as $pay): ?>
                    <a href="<?= url('hr/paychecks/view?id=' . $pay['id']) ?>" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div>
                            <p class="font-medium text-gray-900"><?= e($pay['employee_name'] ?? 'Employee') ?></p>
                            <p class="text-sm text-gray-500"><?= formatDate($pay['pay_period_end'] ?? null) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900"><?= formatCurrency($pay['net_pay'] ?? 0) ?></p>
                            <span class="text-xs px-2 py-1 rounded-full <?= ($pay['status'] ?? '') === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                <?= ucfirst($pay['status'] ?? 'pending') ?>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No paychecks yet</p>
                <?php endif; ?>
            </div>
            
            <a href="<?= url('hr/paychecks') ?>" class="block text-center mt-4 text-sm text-gold-500 hover:text-gold-600">
                View All Paychecks →
            </a>
        </div>
    </div>
</div>
