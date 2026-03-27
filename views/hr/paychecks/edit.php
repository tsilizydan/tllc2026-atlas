<!-- Edit Paycheck Form -->

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?= url('hr/paychecks') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Paychecks
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Paycheck</h1>
        <p class="text-gray-500 mt-1">
            <?= e(($paycheck['first_name'] ?? '') . ' ' . ($paycheck['last_name'] ?? '')) ?>
            (<?= e($paycheck['employee_code'] ?? '') ?>)
        </p>
    </div>

    <form action="<?= url('hr/paychecks/update') ?>" method="POST" class="space-y-6" x-data="paycheckEditForm(<?= (float)($paycheck['base_salary'] ?? 0) ?>, <?= (float)($paycheck['bonuses'] ?? 0) ?>, <?= (float)($paycheck['deductions'] ?? 0) ?>)">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int)($paycheck['id'] ?? 0) ?>">

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pay Period</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Period Start <span class="text-red-500">*</span></label>
                    <input type="date" name="pay_period_start" required x-model="periodStart"
                        value="<?= e($paycheck['pay_period_start'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Period End <span class="text-red-500">*</span></label>
                    <input type="date" name="pay_period_end" required x-model="periodEnd"
                        value="<?= e($paycheck['pay_period_end'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Amounts</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Salary <span class="text-red-500">*</span></label>
                    <input type="number" name="base_salary" x-model.number="baseSalary" step="0.01" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bonuses</label>
                    <input type="number" name="bonuses" x-model.number="bonuses" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deductions</label>
                    <input type="number" name="deductions" x-model.number="deductions" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div class="flex items-end">
                    <div class="w-full p-3 bg-gold-50 rounded-lg">
                        <p class="text-sm text-gray-600">Net Pay</p>
                        <p class="text-xl font-bold text-gold-600" x-text="'$' + netPay.toFixed(2)"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Status & Payment</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="direct_deposit" <?= ($paycheck['payment_method'] ?? '') === 'direct_deposit' ? 'selected' : '' ?>>Direct Deposit</option>
                        <option value="check" <?= ($paycheck['payment_method'] ?? '') === 'check' ? 'selected' : '' ?>>Check</option>
                        <option value="cash" <?= ($paycheck['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="other" <?= ($paycheck['payment_method'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="pending" <?= ($paycheck['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="paid" <?= ($paycheck['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date (if paid)</label>
                    <input type="date" name="payment_date" value="<?= e($paycheck['payment_date'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($paycheck['notes'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('hr/paychecks/view?id=' . ($paycheck['id'] ?? 0)) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-save mr-2"></i>Update Paycheck
            </button>
        </div>
    </form>
</div>

<script>
function paycheckEditForm(initialBase, initialBonuses, initialDeductions) {
    return {
        baseSalary: initialBase,
        bonuses: initialBonuses,
        deductions: initialDeductions,
        periodStart: '',
        periodEnd: '',
        get netPay() {
            return this.baseSalary + this.bonuses - this.deductions;
        }
    }
}
</script>
