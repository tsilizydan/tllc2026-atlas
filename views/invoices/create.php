<!-- Create Invoice Form -->

<div class="max-w-5xl mx-auto" x-data="invoiceForm()">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('invoices') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Invoices
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Create Invoice</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('invoices/store') ?>" method="POST" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Invoice Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-invoice mr-2 text-gold-500"></i>Invoice Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number</label>
                    <input type="text" name="invoice_number" value="<?= e($invoiceNumber ?? '') ?>" readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date <span class="text-red-500">*</span></label>
                    <input type="date" name="issue_date" value="<?= date('Y-m-d') ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client <span class="text-red-500">*</span></label>
                    <select name="client_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Client</option>
                        <?php foreach ($clients ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= (input('client_id') == $id) ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="USD">USD ($)</option>
                        <option value="EUR">EUR (€)</option>
                        <option value="GBP">GBP (£)</option>
                        <option value="KES">KES (KSh)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
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
                            <th class="text-left text-sm font-medium text-gray-500 pb-3 w-1/2">Description</th>
                            <th class="text-center text-sm font-medium text-gray-500 pb-3 w-20">Qty</th>
                            <th class="text-right text-sm font-medium text-gray-500 pb-3 w-32">Unit Price</th>
                            <th class="text-right text-sm font-medium text-gray-500 pb-3 w-32">Total</th>
                            <th class="w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-b border-gray-100">
                                <td class="py-3">
                                    <input type="text" name="item_description[]" x-model="item.description" placeholder="Item description"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" name="item_quantity[]" x-model="item.quantity" min="1" step="1" @input="calculateTotal(index)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-gold-500">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" name="item_price[]" x-model="item.unit_price" min="0" step="0.01" @input="calculateTotal(index)"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-right focus:outline-none focus:ring-2 focus:ring-gold-500">
                                </td>
                                <td class="py-3 px-2 text-right font-medium text-gray-900" x-text="formatCurrency(item.total)"></td>
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
            
            <button type="button" @click="addItem()" class="mt-4 px-4 py-2 border border-dashed border-gray-300 rounded-lg text-gray-500 hover:text-gold-500 hover:border-gold-500 transition w-full">
                <i class="fas fa-plus mr-2"></i>Add Item
            </button>
        </div>
        
        <!-- Summary & Notes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Notes -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <textarea name="notes" rows="4" placeholder="Additional notes for the client..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"></textarea>
                
                <h2 class="text-lg font-semibold text-gray-900 mb-4 mt-6">Terms & Conditions</h2>
                <textarea name="terms" rows="3" placeholder="Payment terms..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">Payment due within 30 days of invoice date.</textarea>
            </div>
            
            <!-- Totals -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Summary</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" x-text="formatCurrency(subtotal)"></span>
                    </div>
                    
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-600">Tax</span>
                            <input type="number" name="tax_rate" x-model="taxRate" min="0" max="100" step="0.1" @input="calculateGrandTotal()"
                                class="w-16 px-2 py-1 border border-gray-300 rounded text-center text-sm focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <span class="text-gray-500 text-sm">%</span>
                        </div>
                        <span class="font-medium" x-text="formatCurrency(taxAmount)"></span>
                    </div>
                    
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Discount</span>
                        <input type="number" name="discount" x-model="discount" min="0" step="0.01" @input="calculateGrandTotal()"
                            class="w-24 px-2 py-1 border border-gray-300 rounded text-right text-sm focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div class="flex justify-between pt-3 text-lg">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-gold-500" x-text="formatCurrency(grandTotal)"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('invoices') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" name="action" value="draft" class="px-6 py-2 border border-gold-500 text-gold-600 rounded-lg hover:bg-gold-50 transition">
                <i class="fas fa-save mr-2"></i>Save as Draft
            </button>
            <button type="submit" name="action" value="send" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-paper-plane mr-2"></i>Create & Send
            </button>
        </div>
    </form>
</div>

<script>
function invoiceForm() {
    return {
        items: [{ description: '', quantity: 1, unit_price: 0, total: 0 }],
        taxRate: 0,
        discount: 0,
        subtotal: 0,
        taxAmount: 0,
        grandTotal: 0,
        
        addItem() {
            this.items.push({ description: '', quantity: 1, unit_price: 0, total: 0 });
        },
        
        removeItem(index) {
            this.items.splice(index, 1);
            this.calculateGrandTotal();
        },
        
        calculateTotal(index) {
            this.items[index].total = this.items[index].quantity * this.items[index].unit_price;
            this.calculateGrandTotal();
        },
        
        calculateGrandTotal() {
            this.subtotal = this.items.reduce((sum, item) => sum + (item.total || 0), 0);
            this.taxAmount = this.subtotal * (this.taxRate / 100);
            this.grandTotal = this.subtotal + this.taxAmount - this.discount;
        },
        
        formatCurrency(amount) {
            return '$' + parseFloat(amount || 0).toFixed(2);
        }
    }
}
</script>
