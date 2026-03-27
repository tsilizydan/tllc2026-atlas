<!-- Asset List Print Template -->
<table>
    <thead>
        <tr>
            <th>Tag</th>
            <th>Asset Name</th>
            <th>Category</th>
            <th>Serial</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php $assets = $assets ?? []; ?>
        <?php if (!empty($assets)): ?>
        <?php foreach ($assets as $asset): ?>
        <tr>
            <td><?= e($asset['asset_tag'] ?? '') ?></td>
            <td><strong><?= e($asset['name'] ?? '') ?></strong></td>
            <td><?= e($asset['category_name'] ?? '-') ?></td>
            <td><?= e($asset['serial_number'] ?? '-') ?></td>
            <td><?= e($asset['employee_name'] ?? '-') ?></td>
            <td>
                <span class="badge badge-<?= ($asset['status'] ?? '') === 'available' ? 'success' : (($asset['status'] ?? '') === 'assigned' ? 'info' : (($asset['status'] ?? '') === 'in_repair' ? 'warning' : 'dark')) ?>">
                    <?= ucfirst(str_replace('_', ' ', $asset['status'] ?? 'available')) ?>
                </span>
            </td>
            <td><?= formatCurrency($asset['purchase_price'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="7" style="text-align: center; color: #999;">No assets found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Assets</span>
        <span><?= count($assets ?? []) ?></span>
    </div>
    <div class="summary-row">
        <span>Total Value</span>
        <span><?= formatCurrency($stats['total_value'] ?? 0) ?></span>
    </div>
</div>
