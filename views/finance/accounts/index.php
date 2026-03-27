<!-- Bank Accounts Management -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('finance') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Finance
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Bank Accounts</h1>
        <p class="text-gray-500 mt-1">Manage your company bank accounts</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <button type="button" onclick="document.getElementById('addAccountModal').classList.remove('hidden')" 
            class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Account
        </button>
    </div>
</div>

<!-- Total Balance -->
<div class="bg-gradient-to-r from-charcoal to-gray-800 rounded-xl p-6 mb-6">
    <p class="text-gray-400 text-sm mb-1">Total Balance</p>
    <p class="text-3xl font-bold text-gold-400"><?= formatCurrency($totalBalance ?? 0) ?></p>
</div>

<!-- Accounts Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (!empty($accounts)): ?>
    <?php foreach ($accounts as $account): ?>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition">
        <div class="p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-gold-50 flex items-center justify-center mr-3">
                        <i class="fas fa-university text-gold-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900"><?= e($account['name']) ?></h3>
                        <p class="text-sm text-gray-500"><?= e($account['bank_name'] ?? 'Bank') ?></p>
                    </div>
                </div>
                <?= statusBadge($account['is_active'] ? 'active' : 'inactive') ?>
            </div>
            
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Account</span>
                    <span class="font-medium text-gray-900"><?= e($account['account_number'] ?? '****') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Type</span>
                    <span class="text-gray-900"><?= ucfirst($account['type'] ?? 'checking') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Currency</span>
                    <span class="text-gray-900"><?= e($account['currency'] ?? 'USD') ?></span>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-center">
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="text-xl font-bold <?= ($account['balance'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                    <?= formatCurrency($account['balance'] ?? 0) ?>
                </p>
            </div>
        </div>
        
        <div class="border-t border-gray-200 px-5 py-3 flex items-center justify-between bg-gray-50">
            <a href="<?= url('finance/accounts/transactions?id=' . $account['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                View Transactions
            </a>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="editAccount(<?= htmlspecialchars(json_encode($account)) ?>)" 
                    class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <form action="<?= url('finance/accounts/delete?id=' . $account['id']) ?>" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this account?')">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-200">
        <i class="fas fa-university text-gray-300 text-5xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No bank accounts</h3>
        <p class="text-gray-500 mb-4">Add your first bank account to start tracking finances.</p>
        <button type="button" onclick="document.getElementById('addAccountModal').classList.remove('hidden')"
            class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Account
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- Add Account Modal -->
<div id="addAccountModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 shadow-xl">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Add Bank Account</h3>
            <button type="button" onclick="document.getElementById('addAccountModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="<?= url('finance/accounts/store') ?>" method="POST" class="p-5 space-y-4">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g., Business Checking"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                <input type="text" name="bank_name" placeholder="e.g., Chase Bank"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                    <input type="text" name="account_number" placeholder="****1234"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="checking">Checking</option>
                        <option value="savings">Savings</option>
                        <option value="credit">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="USD">USD ($)</option>
                        <option value="EUR">EUR (€)</option>
                        <option value="GBP">GBP (£)</option>
                        <option value="CAD">CAD ($)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opening Balance</label>
                    <input type="number" name="balance" step="0.01" value="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                    class="w-4 h-4 text-gold-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Account is active</label>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="document.getElementById('addAccountModal').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    Add Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editAccount(account) {
    // In a real app, this would open an edit modal or redirect to edit page
    window.location.href = '<?= url('finance/accounts/edit') ?>?id=' + account.id;
}
</script>
