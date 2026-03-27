<?php $invoice = $invoice ?? []; $company = $company ?? []; ?>
<h3>Invoice <?= e($invoice['invoice_number'] ?? '') ?></h3>

<div class="info-grid">
    <div class="info-box">
        <label>Invoice Number</label>
        <span><?= e($invoice['invoice_number']) ?></span>
    </div>
    <div class="info-box">
        <label>Issue Date</label>
        <span><?= formatDate($invoice['issue_date'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Due Date</label>
        <span><?= formatDate($invoice['due_date'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Status</label>
        <span class="badge badge-<?= ($invoice['status'] ?? 'draft') === 'paid' ? 'success' : (($invoice['status'] ?? '') === 'overdue' ? 'danger' : 'warning') ?>">
            <?= ucfirst($invoice['status'] ?? 'draft') ?>
        </span>
    </div>
</div>

<!-- Bill To -->
<h3>Bill To</h3>
<div style="margin-bottom: 30px;">
    <strong><?= e($invoice['company_name'] ?? 'Walk-in Client') ?></strong><br>
    <?php if (!empty($invoice['client_contact'])): ?>
    Attn: <?= e($invoice['client_contact']) ?><br>
    <?php endif; ?>
    <?php if (!empty($invoice['client_address'])): ?>
    <?= e($invoice['client_address']) ?><br>
    <?php endif; ?>
    <?php if (!empty($invoice['client_city']) || !empty($invoice['client_country'])): ?>
    <?= e($invoice['client_city'] ?? '') ?><?= !empty($invoice['client_country']) ? ', ' . e($invoice['client_country']) : '' ?><br>
    <?php endif; ?>
    <?php if (!empty($invoice['client_email'])): ?>
    Email: <?= e($invoice['client_email']) ?>
    <?php endif; ?>
</div>

<!-- Line Items -->
<table>
    <thead>
        <tr>
            <th style="width: 50%;">Description</th>
            <th style="text-align: center;">Quantity</th>
            <th style="text-align: right;">Unit Price</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($invoice['items']) && is_array($invoice['items'])): ?>
        <?php foreach ($invoice['items'] as $item): ?>
        <tr>
            <td><?= e($item['description'] ?? '') ?></td>
            <td style="text-align: center;"><?= formatNumber($item['quantity'] ?? 0, 0) ?></td>
            <td style="text-align: right;"><?= formatCurrency($item['unit_price'] ?? 0, $invoice['currency'] ?? 'USD') ?></td>
            <td style="text-align: right;"><?= formatCurrency($item['total'] ?? 0, $invoice['currency'] ?? 'USD') ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="4" style="text-align: center; color: #999;">No items</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Summary -->
<div class="summary-box" style="margin-left: auto; width: 300px;">
    <div class="summary-row">
        <span>Subtotal</span>
        <span><?= formatCurrency($invoice['subtotal'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
    </div>
    <?php if (($invoice['tax_rate'] ?? 0) > 0): ?>
    <div class="summary-row">
        <span>Tax (<?= e($invoice['tax_rate']) ?>%)</span>
        <span><?= formatCurrency($invoice['tax_amount'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
    </div>
    <?php endif; ?>
    <?php if (($invoice['discount'] ?? 0) > 0): ?>
    <div class="summary-row">
        <span>Discount</span>
        <span>-<?= formatCurrency($invoice['discount'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
    </div>
    <?php endif; ?>
    <div class="summary-row">
        <span>Total Due</span>
        <span><?= formatCurrency($invoice['total'] ?? 0, $invoice['currency'] ?? 'USD') ?></span>
    </div>
</div>

<!-- Notes & Terms -->
<?php if (!empty($invoice['notes'])): ?>
<h3>Notes</h3>
<p style="font-size: 11pt;"><?= nl2br(e($invoice['notes'])) ?></p>
<?php endif; ?>

<?php if (!empty($invoice['terms'])): ?>
<h3>Terms & Conditions</h3>
<p style="font-size: 10pt; color: #666;"><?= nl2br(e($invoice['terms'])) ?></p>
<?php endif; ?>

<!-- Payment Info -->
<div style="margin-top: 40px; padding: 20px; background: #f5f5f5; border-left: 4px solid #C9A227;">
    <h4 style="margin: 0 0 10px 0;">Payment Information</h4>
    <p style="font-size: 10pt; color: #666; margin: 0;">
        Please make payment within the due date. For any questions regarding this invoice,<br>
        please contact us at <?= e($company['email'] ?? 'accounts@tsilizy.com') ?>
    </p>
</div>
