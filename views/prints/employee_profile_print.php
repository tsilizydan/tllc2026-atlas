<!-- Employee Profile Print Template -->
<?php $employee = $employee ?? []; ?>
<h3>Employee Profile</h3>

<div class="info-grid">
    <div class="info-box">
        <label>Employee Code</label>
        <span><?= e($employee['employee_code'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Full Name</label>
        <span><?= e(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? '')) ?></span>
    </div>
    <div class="info-box">
        <label>Position</label>
        <span><?= e($employee['position'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Department</label>
        <span><?= e($employee['department'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Email</label>
        <span><?= e($employee['email'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Phone</label>
        <span><?= e($employee['phone'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Hire Date</label>
        <span><?= !empty($employee['hire_date']) ? formatDate($employee['hire_date']) : 'N/A' ?></span>
    </div>
    <div class="info-box">
        <label>Status</label>
        <span class="badge badge-<?= ($employee['status'] ?? '') === 'active' ? 'success' : 'dark' ?>">
            <?= ucfirst($employee['status'] ?? 'active') ?>
        </span>
    </div>
</div>

<?php if (!empty($employee['address'])): ?>
<h3>Address</h3>
<p><?= nl2br(e($employee['address'])) ?></p>
<?php endif; ?>

<?php if (!empty($employee['emergency_contact'])): ?>
<h3>Emergency Contact</h3>
<p><?= nl2br(e($employee['emergency_contact'])) ?></p>
<?php endif; ?>

<?php if (!empty($employee['paychecks'])): ?>
<h3>Recent Paychecks</h3>
<table>
    <thead>
        <tr>
            <th>Period</th>
            <th style="text-align: right;">Base Salary</th>
            <th style="text-align: right;">Bonuses</th>
            <th style="text-align: right;">Deductions</th>
            <th style="text-align: right;">Net Pay</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (array_slice($employee['paychecks'] ?? [], 0, 12) as $paycheck): ?>
        <tr>
            <td><?= formatDate($paycheck['pay_period_start'] ?? '') ?> - <?= formatDate($paycheck['pay_period_end'] ?? '') ?></td>
            <td style="text-align: right;"><?= formatCurrency($paycheck['base_salary'] ?? 0) ?></td>
            <td style="text-align: right;"><?= formatCurrency($paycheck['bonuses'] ?? 0) ?></td>
            <td style="text-align: right;"><?= formatCurrency($paycheck['deductions'] ?? 0) ?></td>
            <td style="text-align: right;"><strong><?= formatCurrency($paycheck['net_pay'] ?? 0) ?></strong></td>
            <td>
                <span class="badge badge-<?= ($paycheck['status'] ?? '') === 'paid' ? 'success' : 'warning' ?>">
                    <?= ucfirst($paycheck['status'] ?? 'pending') ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php if (!empty($employee['notes'])): ?>
<h3>Notes</h3>
<p style="font-size: 10pt; color: #666;"><?= nl2br(e($employee['notes'])) ?></p>
<?php endif; ?>
