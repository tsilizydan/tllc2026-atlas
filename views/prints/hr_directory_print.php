<!-- HR Directory Print Template -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
    <?php $employees = $employees ?? []; ?>
    <?php if (!empty($employees)): ?>
    <?php foreach ($employees as $emp): ?>
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; page-break-inside: avoid;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <div style="width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: bold; color: #666;">
                <?= strtoupper(substr($emp['first_name'] ?? '', 0, 1) . substr($emp['last_name'] ?? '', 0, 1)) ?>
            </div>
            <div>
                <h4 style="margin: 0; color: #333;"><?= e(($emp['first_name'] ?? '') . ' ' . ($emp['last_name'] ?? '')) ?></h4>
                <p style="margin: 0; color: #C9A227; font-size: 10pt;"><?= e($emp['position'] ?? '') ?></p>
            </div>
        </div>
        
        <div style="font-size: 10pt; color: #666;">
            <p style="margin-bottom: 2px;"><strong>Dept:</strong> <?= e($emp['department'] ?? '') ?></p>
            <p style="margin-bottom: 2px;"><strong>Email:</strong> <?= e($emp['email'] ?? '') ?></p>
            <p style="margin-bottom: 2px;"><strong>Phone:</strong> <?= e($emp['phone'] ?? '') ?></p>
            <p style="margin-bottom: 0;"><strong>Code:</strong> <?= e($emp['employee_code'] ?? '') ?></p>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div style="grid-column: span 2; text-align: center; padding: 50px; color: #999;">
        No employees found
    </div>
    <?php endif; ?>
</div>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Employees</span>
        <span><?= count($employees) ?></span>
    </div>
</div>
