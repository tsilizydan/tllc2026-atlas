<!-- Financial Statement Print Template -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Statement - <?= e($period ?? date('F Y')) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11pt; line-height: 1.5; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px; }
        
        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #D4AF37; }
        .logo { max-height: 50px; }
        .report-info { text-align: right; }
        .report-info h2 { font-size: 14pt; color: #2D2D2D; }
        .report-info .period { font-size: 12pt; color: #666; }
        
        /* Summary Cards */
        .summary { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .summary-card { flex: 1; padding: 20px; text-align: center; margin: 0 10px; background: #f9f9f9; border-radius: 5px; }
        .summary-card:first-child { margin-left: 0; }
        .summary-card:last-child { margin-right: 0; }
        .summary-card.income { border-top: 4px solid #10b981; }
        .summary-card.expenses { border-top: 4px solid #ef4444; }
        .summary-card.profit { border-top: 4px solid #D4AF37; }
        .summary-label { font-size: 10pt; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { font-size: 20pt; font-weight: bold; }
        .summary-value.positive { color: #10b981; }
        .summary-value.negative { color: #ef4444; }
        .summary-value.neutral { color: #D4AF37; }
        
        /* Section */
        .section { margin-bottom: 25px; }
        .section h2 { font-size: 12pt; color: #2D2D2D; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 8px; border-bottom: 1px solid #ddd; margin-bottom: 15px; display: flex; justify-content: space-between; }
        .section h2 span { font-size: 14pt; }
        
        /* Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .data-table th, .data-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .data-table th { background: #f9f9f9; font-size: 9pt; color: #666; text-transform: uppercase; }
        .data-table td { font-size: 10pt; }
        .data-table td:last-child { text-align: right; font-weight: 500; }
        .data-table tfoot td { font-weight: bold; border-top: 2px solid #ddd; background: #f9f9f9; }
        .data-table .category-row { background: #fafafa; font-weight: bold; }
        
        /* Amount */
        .amount-positive { color: #10b981; }
        .amount-negative { color: #ef4444; }
        
        /* Charts Section */
        .charts { display: flex; justify-content: space-between; margin-bottom: 30px; gap: 20px; }
        .chart-container { flex: 1; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        .chart-container h3 { font-size: 10pt; color: #666; text-transform: uppercase; margin-bottom: 15px; }
        .bar-chart { }
        .bar-item { display: flex; align-items: center; margin-bottom: 10px; }
        .bar-label { width: 100px; font-size: 9pt; }
        .bar-container { flex: 1; height: 20px; background: #e5e7eb; border-radius: 3px; overflow: hidden; }
        .bar-fill { height: 100%; background: #D4AF37; }
        .bar-value { width: 80px; text-align: right; font-size: 9pt; font-weight: bold; }
        
        /* Notes */
        .notes { background: #fffbeb; border: 1px solid #fcd34d; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .notes h4 { font-size: 10pt; color: #92400e; margin-bottom: 5px; }
        .notes p { font-size: 10pt; color: #78350f; }
        
        /* Footer */
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #ddd; display: flex; justify-content: space-between; font-size: 9pt; color: #999; }
        
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .container { padding: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div>
                <?php if (!empty($company['logo'])): ?>
                <img src="<?= upload($company['logo'] ?? $company['logo_path'] ?? '', 'logo') ?>" alt="<?= e($company['name'] ?? 'TSILIZY LLC') ?>" class="logo">
                <?php else: ?>
                <strong style="color: #D4AF37; font-size: 18pt;"><?= e($company['name'] ?? 'TSILIZY LLC') ?></strong>
                <?php endif; ?>
            </div>
            <div class="report-info">
                <h2>Financial Statement</h2>
                <div class="period"><?= e($period ?? date('F Y')) ?></div>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="summary">
            <div class="summary-card income">
                <div class="summary-label">Total Income</div>
                <div class="summary-value positive"><?= formatCurrency($report['total_income'] ?? 0) ?></div>
            </div>
            <div class="summary-card expenses">
                <div class="summary-label">Total Expenses</div>
                <div class="summary-value negative"><?= formatCurrency($report['total_expenses'] ?? 0) ?></div>
            </div>
            <div class="summary-card profit">
                <div class="summary-label">Net Profit</div>
                <?php $netProfit = ($report['total_income'] ?? 0) - ($report['total_expenses'] ?? 0); ?>
                <div class="summary-value <?= $netProfit >= 0 ? 'positive' : 'negative' ?>">
                    <?= formatCurrency($netProfit) ?>
                </div>
            </div>
        </div>
        
        <!-- Income Section -->
        <div class="section">
            <h2>
                Income 
                <span class="amount-positive"><?= formatCurrency($report['total_income'] ?? 0) ?></span>
            </h2>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report['income_by_category'] ?? [] as $category => $amount): ?>
                    <tr>
                        <td><?= ucfirst($category) ?></td>
                        <td class="amount-positive">+<?= formatCurrency($amount) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Expenses Section -->
        <div class="section">
            <h2>
                Expenses
                <span class="amount-negative"><?= formatCurrency($report['total_expenses'] ?? 0) ?></span>
            </h2>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report['expenses_by_category'] ?? [] as $category => $amount): ?>
                    <tr>
                        <td><?= ucfirst($category) ?></td>
                        <td class="amount-negative">-<?= formatCurrency($amount) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Expense Breakdown Chart -->
        <?php if (!empty($report['expenses_by_category'])): ?>
        <?php 
        $maxExpense = max($report['expenses_by_category']);
        ?>
        <div class="charts">
            <div class="chart-container">
                <h3>Expense Breakdown</h3>
                <div class="bar-chart">
                    <?php foreach ($report['expenses_by_category'] as $category => $amount): ?>
                    <?php $percentage = $maxExpense > 0 ? ($amount / $maxExpense) * 100 : 0; ?>
                    <div class="bar-item">
                        <div class="bar-label"><?= ucfirst($category) ?></div>
                        <div class="bar-container">
                            <div class="bar-fill" style="width: <?= $percentage ?>%"></div>
                        </div>
                        <div class="bar-value"><?= formatCurrency($amount) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Profit Analysis -->
        <div class="section">
            <h2>Profit Analysis</h2>
            
            <table class="data-table">
                <tbody>
                    <tr>
                        <td>Gross Income</td>
                        <td class="amount-positive"><?= formatCurrency($report['total_income'] ?? 0) ?></td>
                    </tr>
                    <tr>
                        <td>Total Expenses</td>
                        <td class="amount-negative">-<?= formatCurrency($report['total_expenses'] ?? 0) ?></td>
                    </tr>
                    <tr class="category-row">
                        <td>Net Profit</td>
                        <td class="<?= $netProfit >= 0 ? 'amount-positive' : 'amount-negative' ?>">
                            <?= formatCurrency($netProfit) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Profit Margin</td>
                        <?php 
                        $margin = ($report['total_income'] ?? 0) > 0 
                            ? ($netProfit / $report['total_income']) * 100 
                            : 0;
                        ?>
                        <td><?= number_format($margin, 1) ?>%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Notes -->
        <div class="notes">
            <h4>Disclaimer</h4>
            <p>This financial statement is for internal use only. Figures are based on recorded transactions and may not reflect all financial activities. For official financial reports, please consult with a certified accountant.</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div>Generated: <?= date('F j, Y, g:i a') ?></div>
            <div><?= e($company['name'] ?? 'TSILIZY LLC') ?> • Confidential</div>
        </div>
    </div>
    
    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>
