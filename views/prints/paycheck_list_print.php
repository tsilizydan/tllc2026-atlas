<!-- Paycheck List Content -->

<table>
    <thead>
        <tr>
            <th>Employee</th>
            <th>Pay Period</th>
            <th>Status</th>
            <th>Payment Date</th>
            <th style="text-align: right;">Base Salary</th>
            <th style="text-align: right;">Bonuses</th>
            <th style="text-align: right;">Deductions</th>
            <th style="text-align: right;">Net Pay</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($paychecks ?? [] as $check): ?>
        <tr>
            <td><?= e($check['employee_name'] ?? (($check['first_name'] ?? '') . ' ' . ($check['last_name'] ?? ''))) ?></td>
            <td><?= formatDate($check['pay_period_start'] ?? '') ?> - <?= formatDate($check['pay_period_end'] ?? '') ?></td>
            <td>
                 <span class="badge badge-<?= ($check['status'] ?? '') === 'paid' ? 'success' : 'warning' ?>">
                    <?= ucfirst($check['status'] ?? 'pending') ?>
                </span>
            </td>
            <td><?= !empty($check['payment_date']) ? formatDate($check['payment_date']) : '-' ?></td>
            <td style="text-align: right;"><?= formatCurrency($check['base_salary'] ?? 0) ?></td>
            <td style="text-align: right;"><?= formatCurrency($check['bonuses'] ?? 0) ?></td>
            <td style="text-align: right;"><?= formatCurrency($check['deductions'] ?? 0) ?></td>
            <td style="text-align: right;"><strong><?= formatCurrency($check['net_pay'] ?? 0) ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Records</span>
        <span><?= count($paychecks ?? []) ?> Paychecks</span>
    </div>
</div>
