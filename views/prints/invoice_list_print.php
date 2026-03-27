<?php $invoices = $invoices ?? []; $status = $status ?? ''; ?>
<?php if ($status): ?>
<div class="info-box" style="margin-bottom: 20px;">
    <label>Status Filter</label>
    <span><?= ucfirst($status) ?></span>
</div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Client</th>
            <th>Date</th>
            <th>Due Date</th>
            <th>Status</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $invoice): ?>
        <tr>
            <td><?= e($invoice['invoice_number'] ?? '') ?></td>
            <td><?= e($invoice['client_name'] ?? '') ?></td>
            <td><?= formatDate($invoice['issue_date'] ?? '') ?></td>
            <td><?= formatDate($invoice['due_date'] ?? '') ?></td>
            <td>
                <span class="badge badge-<?= invoiceStatusType($invoice['status']) ?>">
                    <?= ucfirst($invoice['status']) ?>
                </span>
            </td>
            <td style="text-align: right;"><?= formatCurrency($invoice['total'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Count</span>
        <span><?= count($invoices ?? []) ?> Invoices</span>
    </div>
</div>
