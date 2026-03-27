<!-- Employee Detail View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('hr/employees') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Employees
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= e($employee['first_name'] . ' ' . $employee['last_name']) ?></h1>
        <p class="text-gray-500 mt-1"><?= e($employee['position'] ?? 'No Position') ?> • <?= e($employee['department'] ?? 'No Department') ?></p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('hr/employees/print-profile?id=' . $employee['id']) ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <a href="<?= url('hr/employees/edit?id=' . $employee['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start space-x-6">
                <!-- Photo -->
                <div class="flex-shrink-0">
                    <?php if (!empty($employee['photo'])): ?>
                    <img src="<?= upload($employee['photo']) ?>" alt="<?= e($employee['first_name']) ?>" 
                        class="w-32 h-32 rounded-xl object-cover border-2 border-gray-200">
                    <?php else: ?>
                    <div class="w-32 h-32 rounded-xl bg-gold-100 flex items-center justify-center border-2 border-gold-200">
                        <span class="text-4xl font-bold text-gold-500">
                            <?= strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Basic Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="text-sm font-medium text-gray-500"><?= e($employee['employee_code'] ?? '') ?></span>
                        <?= statusBadge($employee['status'] ?? 'active') ?>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Email</span>
                            <p class="font-medium text-gray-900"><?= e($employee['email'] ?? 'Not set') ?></p>
                        </div>
                        <div>
                            <span class="text-gray-500">Phone</span>
                            <p class="font-medium text-gray-900"><?= e($employee['phone'] ?? 'Not set') ?></p>
                        </div>
                        <div>
                            <span class="text-gray-500">Hire Date</span>
                            <p class="font-medium text-gray-900"><?= formatDate($employee['hire_date'] ?? '') ?></p>
                        </div>
                        <div>
                            <span class="text-gray-500">Employment Type</span>
                            <p class="font-medium text-gray-900"><?= ucwords(str_replace('_', ' ', $employee['employment_type'] ?? 'full_time')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paycheck History -->
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Paycheck History</h2>
                <a href="<?= url('hr/paychecks/create?employee_id=' . $employee['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                    <i class="fas fa-plus mr-1"></i>Add Paycheck
                </a>
            </div>
            
            <?php $paychecks = $paychecks ?? []; ?>
            <?php if (!empty($paychecks)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Base</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net Pay</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach (array_slice($paychecks, 0, 5) as $paycheck): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm text-gray-600">
                                <?= formatDate($paycheck['pay_period_start'] ?? '') ?> - <?= formatDate($paycheck['pay_period_end'] ?? '') ?>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-900 text-right">
                                <?= formatCurrency($paycheck['base_salary'] ?? 0) ?>
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900 text-right">
                                <?= formatCurrency($paycheck['net_pay'] ?? 0) ?>
                            </td>
                            <td class="px-5 py-3">
                                <?= statusBadge($paycheck['status'] ?? 'pending') ?>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="<?= url('hr/paychecks/print?id=' . ($paycheck['id'] ?? 0)) ?>" target="_blank" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (count($paychecks) > 5): ?>
            <div class="p-4 border-t border-gray-200 text-center">
                <a href="<?= url('hr/paychecks?employee_id=' . $employee['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                    View All Paychecks <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-money-check-alt text-3xl mb-2 opacity-50"></i>
                <p>No paychecks yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Compensation -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Compensation</h3>
            
            <div class="space-y-4">
                <div class="text-center p-4 bg-gold-50 rounded-lg">
                    <p class="text-sm text-gray-500">Base Salary</p>
                    <p class="text-2xl font-bold text-gold-500"><?= formatCurrency($employee['salary'] ?? 0) ?></p>
                    <p class="text-xs text-gray-400"><?= ucwords(str_replace('_', '-', $employee['pay_frequency'] ?? 'monthly')) ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">YTD Gross</p>
                        <p class="font-semibold text-gray-900"><?= formatCurrency($stats['ytd_gross'] ?? 0) ?></p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">YTD Net</p>
                        <p class="font-semibold text-gray-900"><?= formatCurrency($stats['ytd_net'] ?? 0) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Emergency Contact -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Emergency Contact</h3>
            
            <?php if (!empty($employee['emergency_contact'])): ?>
            <p class="text-sm text-gray-600 whitespace-pre-line"><?= e($employee['emergency_contact']) ?></p>
            <?php else: ?>
            <p class="text-sm text-gray-400 italic">Not provided</p>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Quick Actions</h3>
            
            <div class="space-y-2">
                <a href="<?= url('hr/paychecks/create?employee_id=' . $employee['id']) ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-money-check-alt text-green-500 w-6"></i>
                    <span class="text-gray-700">Create Paycheck</span>
                </a>
                <a href="<?= url('hr/employees/edit?id=' . $employee['id']) ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-edit text-blue-500 w-6"></i>
                    <span class="text-gray-700">Edit Profile</span>
                </a>
                <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-file-alt text-purple-500 w-6"></i>
                    <span class="text-gray-700">Generate Report</span>
                </a>
            </div>
        </div>
        
        <!-- Meta -->
        <div class="text-xs text-gray-400 space-y-1">
            <p>Created: <?= formatDateTime($employee['created_at'] ?? '') ?></p>
            <?php if (!empty($employee['updated_at'])): ?>
            <p>Updated: <?= formatDateTime($employee['updated_at']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
