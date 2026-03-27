<!-- Paycheck Detail View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('hr/paychecks') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Paychecks
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Paycheck Details</h1>
        <p class="text-gray-500 mt-1">Pay Period: <?= formatDate($paycheck['pay_period_start']) ?> - <?= formatDate($paycheck['pay_period_end']) ?></p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('hr/paychecks/print?id=' . $paycheck['id']) ?>" target="_blank" 
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <?php if ($paycheck['status'] === 'pending'): ?>
        <form action="<?= url('hr/paychecks/process?id=' . $paycheck['id']) ?>" method="POST" class="inline">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                <i class="fas fa-check mr-2"></i>Process Payment
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Status Banner -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <?= statusBadge($paycheck['status']) ?>
                    <span class="text-sm text-gray-500">
                        <?php if (($paycheck['status'] ?? '') === 'paid'): ?>
                        Paid on <?= formatDate($paycheck['payment_date'] ?? $paycheck['updated_at'] ?? '') ?>
                        <?php elseif ($paycheck['status'] === 'pending'): ?>
                        Scheduled for <?= formatDate($paycheck['payment_date'] ?? '') ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-medium text-gray-900"><?= ucfirst($paycheck['payment_method'] ?? 'Direct Deposit') ?></p>
                </div>
            </div>
        </div>
        
        <!-- Earnings -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-200 bg-green-50">
                <h2 class="text-lg font-semibold text-green-800">
                    <i class="fas fa-plus-circle mr-2"></i>Earnings
                </h2>
            </div>
            
            <div class="divide-y divide-gray-100">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Base Salary</p>
                        <p class="text-sm text-gray-500">Regular pay for period</p>
                    </div>
                    <span class="font-semibold text-gray-900"><?= formatCurrency($paycheck['base_salary'] ?? 0) ?></span>
                </div>
                
                <?php if (!empty($paycheck['overtime'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Overtime</p>
                        <p class="text-sm text-gray-500"><?= $paycheck['overtime_hours'] ?? 0 ?> hours @ 1.5x rate</p>
                    </div>
                    <span class="font-semibold text-gray-900"><?= formatCurrency($paycheck['overtime']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['bonus'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Bonus</p>
                        <p class="text-sm text-gray-500"><?= e($paycheck['bonus_description'] ?? 'Performance bonus') ?></p>
                    </div>
                    <span class="font-semibold text-gray-900"><?= formatCurrency($paycheck['bonus']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['commission'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Commission</p>
                        <p class="text-sm text-gray-500">Sales commission</p>
                    </div>
                    <span class="font-semibold text-gray-900"><?= formatCurrency($paycheck['commission']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="p-4 bg-green-50 border-t border-green-100">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-green-800">Gross Pay</span>
                    <span class="text-xl font-bold text-green-600"><?= formatCurrency($paycheck['gross_pay'] ?? 0) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Deductions -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-200 bg-red-50">
                <h2 class="text-lg font-semibold text-red-800">
                    <i class="fas fa-minus-circle mr-2"></i>Deductions
                </h2>
            </div>
            
            <div class="divide-y divide-gray-100">
                <?php if (!empty($paycheck['tax_federal'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Federal Tax</p>
                        <p class="text-sm text-gray-500">Withholding</p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['tax_federal']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['tax_state'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">State Tax</p>
                        <p class="text-sm text-gray-500">Withholding</p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['tax_state']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['social_security'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Social Security (FICA)</p>
                        <p class="text-sm text-gray-500">6.2% of gross</p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['social_security']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['medicare'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Medicare</p>
                        <p class="text-sm text-gray-500">1.45% of gross</p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['medicare']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['health_insurance'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Health Insurance</p>
                        <p class="text-sm text-gray-500">Employee premium</p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['health_insurance']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['retirement'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">401(k) Contribution</p>
                        <p class="text-sm text-gray-500">Retirement savings</p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['retirement']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($paycheck['other_deductions'])): ?>
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="font-medium text-gray-900">Other Deductions</p>
                        <p class="text-sm text-gray-500"><?= e($paycheck['other_deduction_notes'] ?? '') ?></p>
                    </div>
                    <span class="font-semibold text-red-600">-<?= formatCurrency($paycheck['other_deductions']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="p-4 bg-red-50 border-t border-red-100">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-red-800">Total Deductions</span>
                    <span class="text-xl font-bold text-red-600">-<?= formatCurrency($paycheck['total_deductions'] ?? 0) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Net Pay Summary -->
        <div class="bg-gradient-to-r from-charcoal to-gray-800 rounded-xl p-6 text-center">
            <p class="text-gray-400 text-sm mb-1">NET PAY</p>
            <p class="text-4xl font-bold text-gold-400"><?= formatCurrency($paycheck['net_pay'] ?? 0) ?></p>
            <p class="text-gray-400 text-sm mt-2">
                Deposited to <?= ucfirst($paycheck['payment_method'] ?? 'bank account') ?>
            </p>
        </div>
    </div>
    
    <!-- Sidebar -->
    <?php $employee = $employee ?? []; ?>
    <div class="space-y-6">
        <!-- Employee Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Employee</h3>
            
            <div class="flex items-center space-x-4 mb-4">
                <?php if (!empty($employee['photo'])): ?>
                <img src="<?= upload($employee['photo']) ?>" class="w-14 h-14 rounded-full object-cover">
                <?php else: ?>
                <div class="w-14 h-14 rounded-full bg-gold-50 flex items-center justify-center border border-gold-100">
                    <i class="fas fa-user text-gold-500 text-xl"></i>
                </div>
                <?php endif; ?>
                <div>
                    <p class="font-medium text-gray-900"><?= e(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?></p>
                    <p class="text-sm text-gray-500"><?= e($employee['position'] ?? '') ?></p>
                </div>
            </div>
            
            <?php if (!empty($employee['id'])): ?>
            <a href="<?= url('hr/employees/view?id=' . $employee['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                View Profile →
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Pay Period Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Pay Period</h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Start Date</span>
                    <span class="text-gray-900"><?= formatDate($paycheck['pay_period_start']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">End Date</span>
                    <span class="text-gray-900"><?= formatDate($paycheck['pay_period_end']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pay Date</span>
                    <span class="text-gray-900"><?= formatDate($paycheck['payment_date'] ?? '') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pay Type</span>
                    <span class="text-gray-900"><?= ucfirst($paycheck['pay_type'] ?? 'Regular') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Actions</h3>
            
            <div class="space-y-2">
                <a href="<?= url('hr/paychecks/print?id=' . $paycheck['id']) ?>" target="_blank" 
                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-print text-blue-500 w-6"></i>
                    <span class="text-gray-700">Print Pay Stub</span>
                </a>
                <a href="<?= url('hr/paychecks/download?id=' . $paycheck['id']) ?>" 
                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-download text-purple-500 w-6"></i>
                    <span class="text-gray-700">Download PDF</span>
                </a>
                <?php if ($paycheck['status'] === 'paid'): ?>
                <a href="<?= url('hr/paychecks/resend?id=' . $paycheck['id']) ?>" 
                    class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-envelope text-green-500 w-6"></i>
                    <span class="text-gray-700">Email to Employee</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Meta -->
        <div class="text-xs text-gray-400 space-y-1">
            <p>Created: <?= formatDateTime($paycheck['created_at'] ?? '') ?></p>
            <?php if (!empty($paycheck['updated_at'])): ?>
            <p>Updated: <?= formatDateTime($paycheck['updated_at']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
