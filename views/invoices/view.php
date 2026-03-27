<!-- View Invoice -->

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <a href="<?= url('invoices') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
                <i class="fas fa-arrow-left mr-2"></i>Back to Invoices
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Invoice <?= e($invoice['invoice_number']) ?></h1>
            <div class="flex items-center space-x-3 mt-2">
                <?= statusBadge($invoice['status']) ?>
                <span class="text-gray-500">|</span>
                <span class="text-sm text-gray-500">Issued: <?= formatDate($invoice['issue_date']) ?></span>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <a href="<?= url('invoices/print?id=' . $invoice['id']) ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-print mr-2"></i>Print
            </a>
            <?php if (($invoice['status'] ?? '') !== 'paid'): ?>
            <a href="<?= url('invoices/edit?id=' . $invoice['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Invoice Card -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- From -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">From</h3>
                    <?php if (!empty($company['logo_path'])): ?>
                        <img src="<?= upload($company['logo_path'] ?? '', 'logo') ?>" alt="Logo" class="h-12 mb-3 object-contain">
                    <?php endif; ?>
                    <p class="font-semibold text-gray-900"><?= e($company['company_name'] ?? 'TSILIZY LLC') ?></p>
                    <?php if (!empty($company['address'])): ?>
                    <p class="text-gray-600"><?= e($company['address']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($company['email'])): ?>
                    <p class="text-gray-600"><?= e($company['email']) ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- To -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Bill To</h3>
                    <p class="font-semibold text-gray-900"><?= e($invoice['company_name'] ?? 'Walk-in Client') ?></p>
                    <?php if (!empty($invoice['client_contact'])): ?>
                    <p class="text-gray-600">Attn: <?= e($invoice['client_contact']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($invoice['client_address'])): ?>
                    <p class="text-gray-600"><?= e($invoice['client_address']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($invoice['client_email'])): ?>
                    <p class="text-gray-600"><?= e($invoice['client_email']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Invoice Meta -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="font-medium text-gray-900"><?= e($invoice['invoice_number']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Issue Date</p>
                    <p class="font-medium text-gray-900"><?= formatDate($invoice['issue_date']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Due Date</p>
                    <p class="font-medium text-gray-900"><?= formatDate($invoice['due_date']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Amount Due</p>
                    <p class="text-xl font-bold text-gold-500"><?= formatCurrency($invoice['total'], $invoice['currency'] ?? 'USD') ?></p>
                </div>
            </div>
        </div>
        
        <!-- Line Items -->
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left text-sm font-medium text-gray-500 pb-3">Description</th>
                        <th class="text-center text-sm font-medium text-gray-500 pb-3 w-20">Qty</th>
                        <th class="text-right text-sm font-medium text-gray-500 pb-3 w-32">Unit Price</th>
                        <th class="text-right text-sm font-medium text-gray-500 pb-3 w-32">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($invoice['items'] ?? [] as $item): ?>
                    <?php $item = is_array($item) ? $item : []; ?>
                    <tr>
                        <td class="py-4 text-gray-900"><?= e($item['description'] ?? '') ?></td>
                        <td class="py-4 text-center text-gray-600"><?= formatNumber($item['quantity'] ?? 0, 0) ?></td>
                        <td class="py-4 text-right text-gray-600"><?= formatCurrency($item['unit_price'] ?? 0, $invoice['currency'] ?? 'USD') ?></td>
                        <td class="py-4 text-right font-medium text-gray-900"><?= formatCurrency($item['total'] ?? 0, $invoice['currency'] ?? 'USD') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Totals -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium"><?= formatCurrency($invoice['subtotal'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
                        </div>
                        <?php if (($invoice['tax_rate'] ?? 0) > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (<?= e($invoice['tax_rate'] ?? 0) ?>%)</span>
                            <span class="font-medium"><?= formatCurrency($invoice['tax_amount'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (($invoice['discount'] ?? 0) > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-green-600">-<?= formatCurrency($invoice['discount'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-gold-500"><?= formatCurrency($invoice['total'], $invoice['currency'] ?? 'USD') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes & Terms -->
        <?php if (!empty($invoice['notes']) || !empty($invoice['terms'])): ?>
        <div class="p-6 bg-gray-50 border-t border-gray-200">
            <?php if (!empty($invoice['notes'])): ?>
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Notes</h3>
                <p class="text-gray-600"><?= nl2br(e($invoice['notes'])) ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($invoice['terms'])): ?>
            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Terms & Conditions</h3>
                <p class="text-gray-600 text-sm"><?= nl2br(e($invoice['terms'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Actions -->
    <?php if (($invoice['status'] ?? '') !== 'paid'): ?>
    <div class="mt-6 flex items-center justify-end space-x-4">
        <?php if (($invoice['status'] ?? '') === 'draft'): ?>
        <form action="<?= url('invoices/send?id=' . $invoice['id']) ?>" method="POST" class="inline">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
            <button type="submit" class="px-6 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                <i class="fas fa-paper-plane mr-2"></i>Mark as Sent
            </button>
        </form>
        <?php endif; ?>
        
        <form action="<?= url('invoices/mark-paid') ?>" method="POST" class="inline">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
            <input type="hidden" name="id" value="<?= (int)($invoice['id'] ?? 0) ?>">
            <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium">
                <i class="fas fa-check mr-2"></i>Mark as Paid
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>
