<?php $asset = $asset ?? []; $company = $company ?? CompanyProfile::get() ?? []; ?>
<style>
.asset-print .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #C9A227; }
.asset-print .logo { max-height: 60px; }
.asset-print .asset-tag { text-align: right; }
.asset-print .asset-tag h2 { font-size: 12pt; color: #666; margin-bottom: 5px; }
.asset-print .asset-tag .tag { font-size: 16pt; font-weight: bold; color: #C9A227; }
.asset-print .title { margin-bottom: 25px; }
.asset-print .title h1 { font-size: 20pt; color: #2D2D2D; margin-bottom: 5px; }
.asset-print .title .category { font-size: 11pt; color: #666; }
.asset-print .status-banner { background: #C9A227; color: #2D2D2D; text-align: center; padding: 8px; margin-bottom: 25px; font-weight: bold; text-transform: uppercase; font-size: 11pt; }
.asset-print .details-table { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
.asset-print .details-table th, .asset-print .details-table td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #eee; }
.asset-print .details-table th { background: #f9f9f9; font-size: 10pt; color: #666; text-transform: uppercase; width: 140px; }
.asset-print .description { background: #f9f9f9; padding: 15px; margin-top: 20px; border-radius: 5px; line-height: 1.6; }
.asset-print .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #ddd; text-align: center; font-size: 9pt; color: #999; }
</style>
<div class="asset-print">
    <div class="header">
        <div>
            <?php if (!empty($company['logo_path']) || !empty($company['logo'])): ?>
            <img src="<?= upload($company['logo_path'] ?? $company['logo'] ?? '', 'logo') ?>" alt="<?= e($company['company_name'] ?? 'TSILIZY LLC') ?>" class="logo">
            <?php else: ?>
            <h2 style="color: #C9A227; font-size: 22pt;"><?= e($company['company_name'] ?? 'TSILIZY LLC') ?></h2>
            <?php endif; ?>
        </div>
        <div class="asset-tag">
            <h2>Asset Tag</h2>
            <div class="tag"><?= e($asset['asset_tag'] ?? '') ?></div>
        </div>
    </div>
    <div class="title">
        <h1><?= e($asset['name'] ?? 'Asset') ?></h1>
        <div class="category"><?= e($asset['category_name'] ?? '-') ?></div>
    </div>
    <div class="status-banner">Status: <?= strtoupper(str_replace('_', ' ', $asset['status'] ?? 'available')) ?></div>
    <table class="details-table">
        <tr><th>Serial Number</th><td><?= e($asset['serial_number'] ?? '-') ?></td></tr>
        <tr><th>Location</th><td><?= e($asset['location'] ?? '-') ?></td></tr>
        <tr><th>Assigned To</th><td><?= e($asset['employee_name'] ?? '-') ?></td></tr>
        <tr><th>Purchase Date</th><td><?= formatDate($asset['purchase_date'] ?? '') ?: '-' ?></td></tr>
        <tr><th>Purchase Price</th><td><?= formatCurrency($asset['purchase_price'] ?? 0) ?></td></tr>
        <tr><th>Warranty Expiry</th><td><?= formatDate($asset['warranty_expiry'] ?? '') ?: '-' ?></td></tr>
        <tr><th>Assigned At</th><td><?= !empty($asset['assigned_at']) ? formatDateTime($asset['assigned_at']) : '-' ?></td></tr>
    </table>
    <?php if (!empty($asset['description'])): ?>
    <div class="description">
        <h3 style="font-size: 11pt; color: #666; margin-bottom: 10px;">Description</h3>
        <?= nl2br(e($asset['description'])) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($asset['notes'])): ?>
    <div class="description" style="margin-top: 15px;">
        <h3 style="font-size: 11pt; color: #666; margin-bottom: 10px;">Notes</h3>
        <?= nl2br(e($asset['notes'])) ?>
    </div>
    <?php endif; ?>
    <div class="footer">
        Printed on <?= date(DISPLAY_DATE_FORMAT) ?> at <?= date('H:i') ?> · TSILIZY CORE Asset Register
    </div>
</div>
