<!-- Create Income Entry -->

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('finance/income') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Income
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add Income</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('finance/income/store') ?>" method="POST" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="<?= old('date', date('Y-m-d')) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="amount" value="<?= old('amount') ?>" step="0.01" min="0" required
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <input type="text" name="description" value="<?= old('description') ?>" required placeholder="e.g., Payment for web design services"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="invoice">Invoice Payment</option>
                            <option value="service">Service</option>
                            <option value="retainer">Retainer</option>
                            <option value="consulting">Consulting</option>
                            <option value="affiliate">Affiliate/Commission</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client (Optional)</label>
                        <select name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="">No Client</option>
                            <?php foreach ($clients ?? [] as $id => $name): ?>
                            <option value="<?= $id ?>" <?= old('client_id') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice (Optional)</label>
                    <select name="invoice_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Link to Invoice</option>
                        <?php foreach ($invoices ?? [] as $invoice): ?>
                        <option value="<?= $invoice['id'] ?>" <?= old('invoice_id') == $invoice['id'] ? 'selected' : '' ?>>
                            <?= e($invoice['invoice_number']) ?> - <?= formatCurrency($invoice['total']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                    <select name="bank_account_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Account</option>
                        <?php foreach ($bankAccounts ?? [] as $account): ?>
                        <option value="<?= $account['id'] ?>" <?= old('bank_account_id') == $account['id'] ? 'selected' : '' ?>>
                            <?= e($account['name']) ?> (<?= e($account['account_number']) ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" placeholder="Additional notes..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('finance/income') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                <i class="fas fa-plus mr-2"></i>Add Income
            </button>
        </div>
    </form>
</div>
