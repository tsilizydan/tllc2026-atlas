<!-- Paycheck Print Content -->
<?php $paycheck = $paycheck ?? []; ?>
<h3>Pay Stub</h3>

<div class="info-grid">
    <div class="info-box">
        <label>Employee</label>
        <span><?= e(($paycheck['first_name'] ?? '') . ' ' . ($paycheck['last_name'] ?? '')) ?></span>
    </div>
    <div class="info-box">
        <label>Employee Code</label>
        <span><?= e($paycheck['employee_code'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Position</label>
        <span><?= e($paycheck['position'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Department</label>
        <span><?= e($paycheck['department'] ?? 'N/A') ?></span>
    </div>
</div>

<h3>Pay Period Details</h3>
<div class="info-grid">
    <div class="info-box">
        <label>Period Start</label>
        <span><?= formatDate($paycheck['pay_period_start']) ?></span>
    </div>
    <div class="info-box">
        <label>Period End</label>
        <span><?= formatDate($paycheck['pay_period_end']) ?></span>
    </div>
    <div class="info-box">
        <label>Payment Date</label>
        <span><?= !empty($paycheck['payment_date']) ? formatDate($paycheck['payment_date']) : 'Pending' ?></span>
    </div>
    <div class="info-box">
        <label>Status</label>
        <span class="badge badge-<?= ($paycheck['status'] ?? '') === 'paid' ? 'success' : 'warning' ?>">
            <?= ucfirst($paycheck['status'] ?? 'pending') ?>
        </span>
    </div>
</div>

<h3>Earnings & Deductions</h3>
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th style="text-align: right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Base Salary</td>
            <td style="text-align: right;"><?= formatCurrency($paycheck['base_salary']) ?></td>
        </tr>
        <?php if ($paycheck['bonuses'] > 0): ?>
        <tr style="color: green;">
            <td>Bonuses</td>
            <td style="text-align: right;">+<?= formatCurrency($paycheck['bonuses']) ?></td>
        </tr>
        <?php endif; ?>
        <?php if ($paycheck['deductions'] > 0): ?>
        <tr style="color: red;">
            <td>Deductions</td>
            <td style="text-align: right;">-<?= formatCurrency($paycheck['deductions']) ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box" style="margin-left: auto; width: 300px;">
    <div class="summary-row">
        <span>Net Pay</span>
        <span><?= formatCurrency($paycheck['net_pay'] ?? 0) ?></span>
    </div>
</div>

<?php if (!empty($paycheck['payment_method'])): ?>
<p style="margin-top: 20px; font-size: 10pt; color: #666;">
    <strong>Payment Method:</strong> <?= e($paycheck['payment_method']) ?>
</p>
<?php endif; ?>

<?php if (!empty($paycheck['notes'])): ?>
<h3>Notes</h3>
<p style="font-size: 10pt; color: #666;"><?= nl2br(e($paycheck['notes'])) ?></p>
<?php endif; ?>

<div style="margin-top: 60px; border-top: 1px solid #ddd; padding-top: 20px;">
    <div style="display: flex; justify-content: space-between;">
        <div style="width: 45%;">
            <p style="border-top: 1px solid #000; margin-top: 40px; padding-top: 5px; text-align: center; font-size: 10pt;">
                Employee Signature
            </p>
        </div>
        <div style="width: 45%;">
            <p style="border-top: 1px solid #000; margin-top: 40px; padding-top: 5px; text-align: center; font-size: 10pt;">
                Authorized Signature
            </p>
        </div>
    </div>
</div>
