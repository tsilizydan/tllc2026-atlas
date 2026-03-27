<!-- Partner Profile Print Template -->
<div class="info-grid">
    <div class="info-box">
        <label>Company Name</label>
        <span><?= e($partner['company_name'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Contact Person</label>
        <span><?= e($partner['contact_name'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Email</label>
        <span><?= e($partner['email'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Phone</label>
        <span><?= e($partner['phone'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Partnership Type</label>
        <span><?= e($partner['partnership_type'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Status</label>
        <span class="badge badge-<?= ($partner['status'] ?? 'active') === 'active' ? 'success' : 'dark' ?>">
            <?= ucfirst($partner['status'] ?? 'active') ?>
        </span>
    </div>
</div>

<?php if (!empty($partner['address']) || !empty($partner['website'])): ?>
<div style="margin-bottom: 30px;">
    <?php if (!empty($partner['address'])): ?>
    <p><strong>Address:</strong><br><?= e($partner['address']) ?></p>
    <?php endif; ?>
    <?php if (!empty($partner['website'])): ?>
    <p><strong>Website:</strong> <?= e($partner['website']) ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Contracts -->
<?php if (!empty($partner['contracts'])): ?>
<h3>Contracts</h3>
<table>
    <thead>
        <tr>
            <th>Contract Title</th>
            <th>Type</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($partner['contracts'] as $contract): ?>
        <tr>
            <td><?= e($contract['title'] ?? '') ?></td>
            <td><?= e(ucfirst($contract['type'] ?? '')) ?></td>
            <td>
                <span class="badge badge-<?= ($contract['status'] ?? '') === 'active' ? 'success' : (($contract['status'] ?? '') === 'expired' ? 'danger' : 'warning') ?>">
                    <?= ucfirst($contract['status'] ?? 'N/A') ?>
                </span>
            </td>
            <td><?= formatDate($contract['start_date'] ?? '') ?></td>
            <td><?= formatDate($contract['end_date'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<!-- Notes -->
<?php if (!empty($partner['notes'])): ?>
<h3>Notes</h3>
<p><?= nl2br(e($partner['notes'])) ?></p>
<?php endif; ?>
