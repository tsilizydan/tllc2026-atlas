<?php $partners = $partners ?? []; ?>
<table>
    <thead>
        <tr>
            <th>Company</th>
            <th>Contact Person</th>
            <th>Type</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($partners)): ?>
        <?php foreach ($partners as $partner): ?>
        <tr>
            <td><strong><?= e($partner['company_name'] ?? '') ?></strong></td>
            <td><?= e($partner['contact_name'] ?? '') ?></td>
            <td><?= e($partner['partnership_type'] ?? $partner['type'] ?? '') ?></td>
            <td><?= e($partner['email'] ?? '') ?></td>
            <td><?= e($partner['phone'] ?? '') ?></td>
            <td>
                <span class="badge badge-<?= ($partner['status'] ?? '') === 'active' ? 'success' : 'dark' ?>">
                    <?= ucfirst($partner['status'] ?? 'active') ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center; color: #999;">No partners found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Partners</span>
        <span><?= count($partners) ?></span>
    </div>
</div>
