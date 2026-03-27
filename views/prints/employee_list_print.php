<!-- Employee List Print Template -->
<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Position</th>
            <th>Department</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $employees = $employees ?? []; ?>
        <?php if (!empty($employees)): ?>
        <?php foreach ($employees as $emp): ?>
        <tr>
            <td><?= e($emp['employee_code'] ?? '') ?></td>
            <td>
                <strong><?= e(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')) ?></strong>
            </td>
            <td><?= e($emp['position'] ?? '') ?></td>
            <td><?= e($emp['department'] ?? '') ?></td>
            <td><?= e($emp['email'] ?? '') ?></td>
            <td>
                <span class="badge badge-<?= ($emp['status'] ?? '') === 'active' ? 'success' : 'dark' ?>">
                    <?= ucfirst($emp['status'] ?? '') ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center; color: #999;">No employees found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Employees</span>
        <span><?= count($employees) ?></span>
    </div>
</div>
