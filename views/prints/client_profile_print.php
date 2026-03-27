<?php $client = $client ?? []; ?>
<div class="info-grid">
    <div class="info-box">
        <label>Company Name</label>
        <span><?= e($client['company_name'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Contact Person</label>
        <span><?= e($client['contact_name'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Email</label>
        <span><?= e($client['email'] ?? '') ?></span>
    </div>
    <div class="info-box">
        <label>Phone</label>
        <span><?= e($client['phone'] ?? 'N/A') ?></span>
    </div>
    <div class="info-box">
        <label>Status</label>
        <span class="badge badge-<?= ($client['status'] ?? '') === 'active' ? 'success' : 'dark' ?>">
            <?= ucfirst($client['status'] ?? '') ?>
        </span>
    </div>
    <div class="info-box">
        <label>Category</label>
        <span><?= e($client['category'] ?? 'General') ?></span>
    </div>
</div>

<?php if (!empty($client['address'])): ?>
<div style="margin-bottom: 30px;">
    <strong>Address:</strong><br>
    <?= e($client['address']) ?><br>
    <?= e($client['city']) ?><?= !empty($client['country']) ? ', ' . e($client['country']) : '' ?>
</div>
<?php endif; ?>

<!-- Projects -->
<?php if (!empty($client['projects'])): ?>
<h3>Active Projects</h3>
<table>
    <thead>
        <tr>
            <th>Project Name</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Budget</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($client['projects'] as $project): ?>
        <tr>
            <td><?= e($project['name']) ?></td>
            <td>
                <span class="badge badge-<?= $project['status'] === 'completed' ? 'success' : ($project['status'] === 'planning' ? 'info' : 'warning') ?>">
                    <?= ucfirst($project['status']) ?>
                </span>
            </td>
            <td><?= formatDate($project['start_date']) ?></td>
            <td><?= formatCurrency($project['budget']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<!-- Invoices -->
<?php if (!empty($client['invoices'])): ?>
<h3>Recent Invoices</h3>
<table>
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Date</th>
            <th>Status</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($client['invoices'] ?? [] as $invoice): ?>
        <tr>
            <td><?= e($invoice['invoice_number'] ?? '') ?></td>
            <td><?= formatDate($invoice['issue_date'] ?? '') ?></td>
            <td>
                <span class="badge badge-<?= ($invoice['status'] ?? '') === 'paid' ? 'success' : (($invoice['status'] ?? '') === 'overdue' ? 'danger' : 'warning') ?>">
                    <?= ucfirst($invoice['status'] ?? '') ?>
                </span>
            </td>
            <td><?= formatCurrency($invoice['total'] ?? 0, $invoice['currency'] ?? 'USD') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<!-- Notes -->
<?php if (!empty($client['notes'])): ?>
<h3>Notes</h3>
<p><?= nl2br(e($client['notes'])) ?></p>
<?php endif; ?>
