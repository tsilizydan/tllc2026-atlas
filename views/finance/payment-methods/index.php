<!-- Payment Methods -->

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Methods</h1>
            <p class="text-sm text-gray-500 mt-1">Manage payment options for invoices and expenses</p>
        </div>
        <button onclick="document.getElementById('addMethodModal').classList.remove('hidden')" 
            class="mt-4 sm:mt-0 px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Method
        </button>
    </div>

    <!-- Methods Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($methods as $method): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900"><?= e($method['name']) ?></h3>
                        <p class="text-xs text-gray-500 capitalize"><?= e($method['type']) ?></p>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 transition">
                        <form action="<?= url('finance/payment-methods/toggle?id=' . $method['id']) ?>" method="POST">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <?= $method['is_active'] ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </form>
                        <form action="<?= url('finance/payment-methods/delete?id=' . $method['id']) ?>" method="POST"
                            onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($method['details'])): ?>
            <p class="text-sm text-gray-600 mb-4 bg-gray-50 p-3 rounded-lg">
                <?= nl2br(e($method['details'])) ?>
            </p>
            <?php endif; ?>
            
            <div class="flex items-center justify-between text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $method['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                    <?= $method['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Add Modal -->
    <div id="addMethodModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4" @click.away="document.getElementById('addMethodModal').classList.add('hidden')">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Add Payment Method</h3>
                <button onclick="document.getElementById('addMethodModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="<?= url('finance/payment-methods/store') ?>" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required placeholder="e.g. M-Pesa, Stripe"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="bank">Bank Transfer</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="cash">Cash</option>
                        <option value="crypto">Cryptocurrency</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Details (Optional)</label>
                    <textarea name="details" rows="3" placeholder="Account number, wallet address, instructions..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" onclick="document.getElementById('addMethodModal').classList.add('hidden')" 
                        class="mr-3 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 font-medium">Add Method</button>
                </div>
            </form>
        </div>
    </div>
</div>
