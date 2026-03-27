<!-- Create Paycheck Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('hr/paychecks') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Paychecks
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Create Paycheck</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('hr/paychecks/store') ?>" method="POST" class="space-y-6" x-data="paycheckForm()">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Employee & Period -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user mr-2 text-gold-500"></i>Employee & Pay Period
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee <span class="text-red-500">*</span></label>
                    <select name="employee_id" required x-model="employeeId" @change="loadEmployeeSalary()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Employee</option>
                        <?php foreach ($employees ?? [] as $emp): ?>
                        <option value="<?= $emp['id'] ?>" data-salary="<?= $emp['salary'] ?? 0 ?>" <?= ($selectedEmployee ?? '') == $emp['id'] ? 'selected' : '' ?>>
                            <?= e($emp['first_name'] . ' ' . $emp['last_name']) ?> (<?= e($emp['employee_code'] ?? '') ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Period Start <span class="text-red-500">*</span></label>
                    <input type="date" name="pay_period_start" required x-model="periodStart"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Period End <span class="text-red-500">*</span></label>
                    <input type="date" name="pay_period_end" required x-model="periodEnd"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                    <input type="date" name="payment_date" x-model="paymentDate"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>
        
        <!-- Earnings -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-dollar-sign mr-2 text-gold-500"></i>Earnings
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Salary <span class="text-red-500">*</span></label>
                    <input type="number" name="base_salary" x-model.number="baseSalary" step="0.01" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Overtime</label>
                    <input type="number" name="overtime" x-model.number="overtime" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bonuses</label>
                    <input type="number" name="bonuses" x-model.number="bonuses" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commissions</label>
                    <input type="number" name="commissions" x-model.number="commissions" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Other Earnings</label>
                    <input type="number" name="other_earnings" x-model.number="otherEarnings" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="flex items-end">
                    <div class="w-full p-3 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600">Gross Pay</p>
                        <p class="text-xl font-bold text-green-600" x-text="'$' + grossPay.toFixed(2)"></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Deductions -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-minus-circle mr-2 text-gold-500"></i>Deductions
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax Withholding</label>
                    <input type="number" name="tax" x-model.number="tax" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Insurance</label>
                    <input type="number" name="insurance" x-model.number="insurance" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Retirement</label>
                    <input type="number" name="retirement" x-model.number="retirement" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Other Deductions</label>
                    <input type="number" name="other_deductions" x-model.number="otherDeductions" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="flex items-end">
                    <div class="w-full p-3 bg-red-50 rounded-lg">
                        <p class="text-sm text-gray-600">Total Deductions</p>
                        <p class="text-xl font-bold text-red-600" x-text="'$' + totalDeductions.toFixed(2)"></p>
                    </div>
                </div>
                
                <div class="flex items-end">
                    <div class="w-full p-3 bg-gold-50 rounded-lg">
                        <p class="text-sm text-gray-600">Net Pay</p>
                        <p class="text-xl font-bold text-gold-500" x-text="'$' + netPay.toFixed(2)"></p>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="deductions" :value="totalDeductions.toFixed(2)">
            <input type="hidden" name="net_pay" :value="netPay.toFixed(2)">
        </div>
        
        <!-- Notes -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-sticky-note mr-2 text-gold-500"></i>Notes
            </h2>
            
            <textarea name="notes" rows="3" placeholder="Additional notes..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"></textarea>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('hr/paychecks') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" name="status" value="pending" class="px-6 py-2 border border-gold-500 text-gold-600 rounded-lg hover:bg-gold-50 transition">
                Save as Pending
            </button>
            <button type="submit" name="status" value="paid" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-check mr-2"></i>Create & Mark Paid
            </button>
        </div>
    </form>
</div>

<script>
function paycheckForm() {
    return {
        employeeId: '<?= $selectedEmployee ?? '' ?>',
        periodStart: '',
        periodEnd: '',
        paymentDate: '',
        baseSalary: <?= $defaultSalary ?? 0 ?>,
        overtime: 0,
        bonuses: 0,
        commissions: 0,
        otherEarnings: 0,
        tax: 0,
        insurance: 0,
        retirement: 0,
        otherDeductions: 0,
        
        get grossPay() {
            return this.baseSalary + this.overtime + this.bonuses + this.commissions + this.otherEarnings;
        },
        
        get totalDeductions() {
            return this.tax + this.insurance + this.retirement + this.otherDeductions;
        },
        
        get netPay() {
            return this.grossPay - this.totalDeductions;
        },
        
        loadEmployeeSalary() {
            const select = document.querySelector('select[name="employee_id"]');
            const option = select.options[select.selectedIndex];
            if (option && option.dataset.salary) {
                this.baseSalary = parseFloat(option.dataset.salary) || 0;
            }
        }
    }
}
</script>
