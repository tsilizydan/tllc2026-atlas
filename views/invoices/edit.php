<!-- Edit Invoice Form -->

<div class="max-w-5xl mx-auto" x-data="invoiceForm()">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('invoices/view?id=' . $invoice['id']) ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Invoice
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Invoice #<?= e($invoice['invoice_number']) ?></h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('invoices/update?id=' . $invoice['id']) ?>" method="POST" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Invoice Header -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-invoice mr-2 text-gold-500"></i>Invoice Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number</label>
                    <input type="text" value="<?= e($invoice['invoice_number']) ?>" readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client <span class="text-red-500">*</span></label>
                    <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Client</option>
                        <?php foreach ($clients ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $invoice['client_id'] == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="draft" <?= $invoice['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="sent" <?= $invoice['status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                        <option value="paid" <?= $invoice['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                        <option value="overdue" <?= $invoice['status'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        <option value="cancelled" <?= $invoice['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date <span class="text-red-500">*</span></label>
                    <input type="date" name="issue_date" value="<?= e(!empty($invoice['issue_date']) ? date('Y-m-d', strtotime($invoice['issue_date'])) : date('Y-m-d')) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="<?= e(!empty($invoice['due_date']) ? date('Y-m-d', strtotime($invoice['due_date'])) : date('Y-m-d', strtotime('+30 days'))) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project (Optional)</label>
                    <select name="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">No Project</option>
                        <?php foreach ($projects ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= ($invoice['project_id'] ?? '') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Line Items -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-list mr-2 text-gold-500"></i>Line Items
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left text-sm font-medium text-gray-500 pb-3">Description</th>
                            <th class="text-right text-sm font-medium text-gray-500 pb-3 w-24">Qty</th>
                            <th class="text-right text-sm font-medium text-gray-500 pb-3 w-32">Rate</th>
                            <th class="text-right text-sm font-medium text-gray-500 pb-3 w-32">Amount</th>
                            <th class="w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-b border-gray-100">
                                <td class="py-3 pr-3">
                                    <input type="text" name="item_description[]" x-model="item.description" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
                                        placeholder="Item description">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" name="item_quantity[]" x-model.number="item.quantity" min="0.01" step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-right focus:outline-none focus:ring-2 focus:ring-gold-500">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" name="item_price[]" x-model.number="item.rate" min="0" step="0.01"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-right focus:outline-none focus:ring-2 focus:ring-gold-500">
                                </td>
                                <td class="py-3 px-2 text-right font-medium text-gray-900" x-text="'$' + (item.quantity * item.rate).toFixed(2)"></td>
                                <td class="py-3 pl-2">
                                    <button type="button" @click="removeItem(index)" class="p-2 text-red-500 hover:text-red-700" x-show="items.length > 1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <button type="button" @click="addItem()" class="mt-4 text-sm text-gold-500 hover:text-gold-600">
                <i class="fas fa-plus mr-1"></i>Add Line Item
            </button>
        </div>
        
        <!-- Totals -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex justify-end">
                <div class="w-full md:w-80 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Tax Rate (%)</span>
                        <input type="number" name="tax_rate" x-model.number="taxRate" min="0" max="100" step="0.01"
                            class="w-20 px-2 py-1 border border-gray-300 rounded text-right focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax Amount</span>
                        <span class="font-medium text-gray-900" x-text="'$' + taxAmount.toFixed(2)"></span>
                    </div>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Discount</span>
                        <input type="number" name="discount" x-model.number="discount" min="0" step="0.01"
                            class="w-24 px-2 py-1 border border-gray-300 rounded text-right focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div class="border-t border-gray-200 pt-3 flex justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-gold-500" x-text="'$' + total.toFixed(2)"></span>
                    </div>
                    
                    <input type="hidden" name="subtotal" :value="subtotal.toFixed(2)">
                    <input type="hidden" name="tax_amount" :value="taxAmount.toFixed(2)">
                    <input type="hidden" name="total" :value="total.toFixed(2)">
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-sticky-note mr-2 text-gold-500"></i>Notes & Terms
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes to Client</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($invoice['notes'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                    <textarea name="terms" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($invoice['terms'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <form action="<?= url('invoices/delete?id=' . $invoice['id']) ?>" method="POST" class="inline"
                onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete Invoice
                </button>
            </form>
            
            <div class="flex items-center space-x-4">
                <a href="<?= url('invoices/view?id=' . $invoice['id']) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function invoiceForm() {
    return {
        items: <?= json_encode(!empty($invoice['items']) ? array_map(function ($item) {
            return [
                'description' => $item['description'] ?? '',
                'quantity' => (float)($item['quantity'] ?? 1),
                'rate' => (float)($item['unit_price'] ?? 0)
            ];
        }, $invoice['items']) : [['description' => '', 'quantity' => 1, 'rate' => 0]]) ?>,
        taxRate: <?= (float)($invoice['tax_rate'] ?? 0) ?>,
        discount: <?= (float)($invoice['discount'] ?? 0) ?>,
        
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.rate), 0);
        },
        
        get taxAmount() {
            return this.subtotal * (this.taxRate / 100);
        },
        
        get total() {
            return this.subtotal + this.taxAmount - this.discount;
        },
        
        addItem() {
            this.items.push({ description: '', quantity: 1, rate: 0 });
        },
        
        removeItem(index) {
            this.items.splice(index, 1);
        }
    }
}
</script>
