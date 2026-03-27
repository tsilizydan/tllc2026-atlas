<!-- Client List Print Template -->
<?php $clients = $clients ?? []; ?>
<h3>Client Directory</h3>
<p style="color: #666; margin-bottom: 20px;">
    Total: <?= count($clients) ?> clients | As of <?= e($printDate ?? date(DISPLAY_DATE_FORMAT)) ?>
</p>
<table>
    <thead>
        <tr>
            <th>Company Name</th>
            <th>Contact Person</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Location</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clients as $client): ?>
        <tr>
            <td>
                <strong><?= e($client['company_name'] ?? '') ?></strong>
                <?php if (!empty($client['website'])): ?>
                <br><small style="color: #666;"><?= e($client['website']) ?></small>
                <?php endif; ?>
            </td>
            <td><?= e($client['contact_name'] ?? '-') ?></td>
            <td><?= e($client['email'] ?? '-') ?></td>
            <td><?= e($client['phone'] ?? '-') ?></td>
            <td>
                <?= e($client['city'] ?? '') ?>
                <?= !empty($client['country']) ? ', ' . e($client['country']) : '' ?>
            </td>
            <td>
                <span class="badge badge-<?= $client['status'] === 'active' ? 'success' : 'dark' ?>">
                    <?= ucfirst($client['status'] ?? 'active') ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
