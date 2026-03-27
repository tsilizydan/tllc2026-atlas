<?php $contract = $contract ?? []; $company = $company ?? CompanyProfile::get() ?? []; ?>
<style>
.contract-print .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 3px solid #D4AF37; }
.contract-print .logo { max-height: 60px; }
.contract-print .contract-number { text-align: right; }
.contract-print .contract-number h2 { font-size: 14pt; color: #666; margin-bottom: 5px; }
.contract-print .contract-number .number { font-size: 18pt; font-weight: bold; color: #D4AF37; }
.contract-print .title { text-align: center; margin-bottom: 30px; }
.contract-print .title h1 { font-size: 24pt; color: #2D2D2D; margin-bottom: 10px; }
.contract-print .title .type { font-size: 12pt; color: #666; text-transform: uppercase; letter-spacing: 2px; }
.contract-print .status-banner { background: #D4AF37; color: #2D2D2D; text-align: center; padding: 10px; margin-bottom: 30px; font-weight: bold; text-transform: uppercase; }
.contract-print .parties { display: flex; justify-content: space-between; margin-bottom: 30px; }
.contract-print .party { width: 48%; }
.contract-print .party h3 { font-size: 10pt; color: #666; text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
.contract-print .party-name { font-size: 14pt; font-weight: bold; margin-bottom: 5px; }
.contract-print .party-info { font-size: 10pt; color: #666; }
.contract-print .details-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
.contract-print .details-table th, .contract-print .details-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
.contract-print .details-table th { background: #f9f9f9; font-size: 10pt; color: #666; text-transform: uppercase; width: 150px; }
.contract-print .signatures { display: flex; justify-content: space-between; margin-top: 60px; }
.contract-print .signature { width: 45%; }
.contract-print .signature h4 { font-size: 10pt; color: #666; text-transform: uppercase; margin-bottom: 40px; }
.contract-print .signature-line { border-top: 1px solid #333; padding-top: 10px; }
.contract-print .contract-footer { margin-top: 60px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 9pt; color: #999; }
</style>
<div class="contract-print">
    <div class="header">
        <div>
            <?php if (!empty($company['logo_path']) || !empty($company['logo'])): ?>
            <img src="<?= upload($company['logo_path'] ?? $company['logo'] ?? '', 'logo') ?>" alt="<?= e($company['company_name'] ?? 'TSILIZY LLC') ?>" class="logo">
            <?php else: ?>
            <h2 style="color: #D4AF37; font-size: 24pt;"><?= e($company['company_name'] ?? 'TSILIZY LLC') ?></h2>
            <?php endif; ?>
        </div>
        <div class="contract-number">
            <h2>Contract</h2>
            <div class="number"><?= e($contract['contract_number'] ?? '') ?></div>
        </div>
    </div>
    <div class="title">
        <h1><?= e($contract['title'] ?? '') ?></h1>
        <div class="type"><?= ucfirst($contract['type'] ?? 'Service Agreement') ?></div>
    </div>
    <div class="status-banner">Status: <?= strtoupper($contract['status'] ?? 'Draft') ?></div>
    <div class="parties">
        <div class="party">
            <h3>Party One</h3>
            <div class="party-name"><?= e($company['company_name'] ?? 'TSILIZY LLC') ?></div>
            <div class="party-info">
                <?= e($company['address'] ?? '') ?><br>
                <?= e($company['email'] ?? '') ?><br>
                <?= e($company['phone'] ?? '') ?>
            </div>
        </div>
        <div class="party">
            <h3>Party Two</h3>
            <div class="party-name"><?= e($contract['company_name'] ?? $contract['partner_name'] ?? 'N/A') ?></div>
            <div class="party-info">
                <?= e($contract['contact_email'] ?? $contract['client_contact'] ?? '') ?><br>
                <?= e($contract['contact_phone'] ?? '') ?>
            </div>
        </div>
    </div>
    <table class="details-table">
        <tr><th>Effective Date</th><td><?= formatDate($contract['start_date'] ?? '') ?></td></tr>
        <tr><th>Expiration Date</th><td><?= formatDate($contract['end_date'] ?? '') ?></td></tr>
        <tr><th>Contract Value</th><td><?= formatCurrency($contract['value'] ?? 0) ?></td></tr>
        <tr><th>Auto-Renewal</th><td><?= !empty($contract['auto_renew']) ? 'Yes' : 'No' ?></td></tr>
    </table>
    <?php if (!empty($contract['description'])): ?>
    <div style="margin-bottom: 30px;">
        <h3 style="font-size: 12pt; color: #666; margin-bottom: 10px;">Terms & Description</h3>
        <div style="background: #f9f9f9; padding: 20px; border-radius: 5px; line-height: 1.8;"><?= nl2br(e($contract['description'])) ?></div>
    </div>
    <?php endif; ?>
    <div class="signatures">
        <div class="signature">
            <h4>For <?= e($company['company_name'] ?? 'TSILIZY LLC') ?></h4>
            <div class="signature-line">
                <div style="font-weight: bold;">___________________________</div>
                <div style="font-size: 10pt; color: #666;">Authorized Representative</div>
                <div style="font-size: 10pt; color: #666; margin-top: 10px;">Date: _______________</div>
            </div>
        </div>
        <div class="signature">
            <h4>For <?= e($contract['company_name'] ?? $contract['partner_name'] ?? 'Second Party') ?></h4>
            <div class="signature-line">
                <div style="font-weight: bold;">___________________________</div>
                <div style="font-size: 10pt; color: #666;">Authorized Representative</div>
                <div style="font-size: 10pt; color: #666; margin-top: 10px;">Date: _______________</div>
            </div>
        </div>
    </div>
    <div class="contract-footer">
        <p>Generated: <?= date('F j, Y, g:i a') ?></p>
        <p><?= e($company['company_name'] ?? 'TSILIZY LLC') ?> &bull; <?= e($company['website'] ?? '') ?></p>
    </div>
</div>
