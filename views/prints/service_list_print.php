<!-- Service List Print Template -->
<h3>Service Catalog</h3>
<p style="color: #666; margin-bottom: 20px;">
    Total: <?= count($services ?? []) ?> services | As of <?= e($printDate ?? date(DISPLAY_DATE_FORMAT)) ?>
</p>

<table>
    <thead>
        <tr>
            <th>Service Name</th>
            <th>Description</th>
            <th>Price Range</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
        <tr>
            <td><strong><?= e($service['name'] ?? '') ?></strong></td>
            <td><?= e(mb_substr($service['description'] ?? '', 0, 100)) ?><?= mb_strlen($service['description'] ?? '') > 100 ? '...' : '' ?></td>
            <td><?= e($service['price_range'] ?? '-') ?></td>
            <td>
                <span class="badge badge-<?= ($service['is_active'] ?? 1) ? 'success' : 'dark' ?>">
                    <?= ($service['is_active'] ?? 1) ? 'Active' : 'Inactive' ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="4" style="text-align: center; color: #999;">No services found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Services</span>
        <span><?= count($services ?? []) ?></span>
    </div>
</div>
