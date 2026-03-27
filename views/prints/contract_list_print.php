<!-- Contract List Print Template -->
<table>
    <thead>
        <tr>
            <th>Ref #</th>
            <th>Title</th>
            <th>Type</th>
            <th>Client/Partner</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $contracts = $contracts ?? []; ?>
        <?php if (!empty($contracts)): ?>
        <?php foreach ($contracts as $contract): ?>
        <tr>
            <td><?= e($contract['contract_number'] ?? $contract['reference_number'] ?? '') ?></td>
            <td><strong><?= e($contract['title']) ?></strong></td>
            <td><?= ucfirst($contract['type']) ?></td>
            <td><?= e($contract['client_name'] ?? $contract['partner_name'] ?? '-') ?></td>
            <td><?= formatDate($contract['start_date'] ?? '') ?></td>
            <td><?= formatDate($contract['end_date'] ?? '') ?></td>
            <td>
                <span class="badge badge-<?= ($contract['status'] ?? '') === 'active' ? 'success' : (($contract['status'] ?? '') === 'expired' ? 'danger' : 'warning') ?>">
                    <?= ucfirst($contract['status'] ?? '') ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="7" style="text-align: center; color: #999;">No contracts found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Contracts</span>
        <span><?= count($contracts ?? []) ?></span>
    </div>
</div>
