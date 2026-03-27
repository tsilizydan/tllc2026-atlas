<!-- Create Expense Entry -->

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('finance/expenses') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Expenses
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add Expense</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('finance/expenses/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                    <input type="text" name="description" value="<?= old('description') ?>" required placeholder="e.g., Adobe Creative Cloud Subscription"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="software">Software & Subscriptions</option>
                            <option value="payroll">Payroll</option>
                            <option value="marketing">Marketing & Advertising</option>
                            <option value="office">Office Supplies</option>
                            <option value="travel">Travel & Transportation</option>
                            <option value="utilities">Utilities</option>
                            <option value="equipment">Equipment</option>
                            <option value="professional">Professional Services</option>
                            <option value="taxes">Taxes & Fees</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor/Payee</label>
                        <input type="text" name="vendor" value="<?= old('vendor') ?>" placeholder="e.g., Adobe Inc."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Account</label>
                        <select name="bank_account_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="">Select Account</option>
                            <?php foreach ($bankAccounts ?? [] as $account): ?>
                            <option value="<?= $account['id'] ?>" <?= old('bank_account_id') == $account['id'] ? 'selected' : '' ?>>
                                <?= e($account['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project (Optional)</label>
                        <select name="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="">Link to Project</option>
                            <?php foreach ($projects ?? [] as $id => $name): ?>
                            <option value="<?= $id ?>" <?= old('project_id') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Receipt Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receipt</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                        <input type="file" name="receipt" id="receipt" accept="image/*,.pdf" class="hidden">
                        <label for="receipt" class="cursor-pointer">
                            <i class="fas fa-receipt text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-600">Click to upload receipt</p>
                            <p class="text-xs text-gray-400">Image or PDF up to 5MB</p>
                        </label>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1" class="w-4 h-4 text-gold-500 border-gray-300 rounded">
                    <label for="is_recurring" class="text-sm text-gray-700">This is a recurring expense</label>
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
            <a href="<?= url('finance/expenses') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium">
                <i class="fas fa-minus-circle mr-2"></i>Add Expense
            </button>
        </div>
    </form>
</div>
