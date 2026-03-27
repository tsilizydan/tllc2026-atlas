<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Print') ?> - TSILIZY CORE</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
        }
        
        /* Screen Preview (A4 style) */
        @media screen {
            body {
                background: #525659;
                padding: 20px 0;
            }
            .page-container {
                background: white;
                width: 210mm;
                min-height: 297mm;
                margin: 0 auto;
                padding: 1.5cm; /* Reduced padding */
                box-shadow: 0 0 10px rgba(0,0,0,0.5);
                position: relative;
            }
        }

        /* Print Override */
        @media print {
            @page {
                margin: 0.5cm;
                size: A4;
            }
            body {
                background: white;
                padding: 0;
                -webkit-print-color-adjust: exact;
            }
            .page-container {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
            /* Avoid breaks */
            tr, .item-row {
                break-inside: avoid;
            }
        }
        
        /* Typography - Compact */
        h1 { font-size: 16pt; margin-bottom: 5px; }
        h2 { font-size: 13pt; margin-bottom: 5px; }
        h3 { font-size: 11pt; margin-bottom: 3px; }
        p, td, th { font-size: 9pt; line-height: 1.3; } /* Smaller font */
        
        /* Print Header - Compact */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10px;
            border-bottom: 2px solid #C9A227;
            margin-bottom: 15px;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background: #f5f5f5;
            font-weight: 600;
            color: #333;
        }
        
        tr:nth-child(even) {
            background: #fafafa;
        }
        
        /* Typography */
        h1, h2, h3, h4 {
            color: #333;
            margin-bottom: 15px;
        }
        
        h3 {
            font-size: 14pt;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        
        p {
            margin-bottom: 10px;
        }
        
        /* Info Boxes */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        
        .info-box {
            background: #f9f9f9;
            padding: 15px;
            border-left: 3px solid #C9A227;
        }
        
        .info-box label {
            display: block;
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .info-box span {
            font-weight: 600;
            color: #333;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 9pt;
            font-weight: 600;
            border-radius: 3px;
        }
        
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-dark { background: #d6d8d9; color: #1b1e21; }
        
        /* Print Footer */
        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 30px;
            border-top: 1px solid #ddd;
            background: #fff;
            font-size: 9pt;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
        
        /* Summary Section */
        .summary-box {
            background: #f5f5f5;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 14pt;
            color: #C9A227;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Print Styles */
        @media print {
            body {
                padding: 0;
            }
            
            @page {
                margin: 2cm;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-footer {
                position: fixed;
            }
        }
        
        /* Print Button (no-print) */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #C9A227;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 12pt;
            cursor: pointer;
            border-radius: 5px;
        }
        
        .print-button:hover {
            background: #b8911f;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Print Button -->
    <button type="button" class="print-button no-print">
        <i class="fas fa-print"></i> Print
    </button>
    
    <div class="page-container">
        <!-- Print Header -->
        <div class="print-header">
            <div class="company-info">
                <?php $company = $company ?? CompanyProfile::get() ?? []; ?>
                <img src="<?= upload($company['logo_path'] ?? '', 'logo') ?>" alt="Logo" style="max-height: 60px; margin-bottom: 10px; display: block;">
                <h1><?= e($company['company_name'] ?? 'TSILIZY LLC') ?></h1>
                <?php if (!empty($company['address'])): ?>
                    <p><?= e($company['address']) ?><?= !empty($company['city']) ? ', ' . e($company['city']) : '' ?></p>
                <?php endif; ?>
                <?php if (!empty($company['phone'])): ?>
                    <p>Tel: <?= e($company['phone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($company['email'])): ?>
                    <p>Email: <?= e($company['email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="document-info">
                <h2><?= e($pageTitle ?? 'Document') ?></h2>
                <p>Generated: <?= e($printDate ?? date(DISPLAY_DATE_FORMAT)) ?></p>
            </div>
        </div>
        
        <!-- Content -->
        <div class="print-content">
            <?= $content ?? '' ?>
        </div>
        
        <!-- Print Footer -->
        <div class="print-footer">
            <span>&copy; <?= date('Y') ?> <?= e($company['company_name'] ?? 'TSILIZY LLC') ?></span>
            <span>TSILIZY CORE - Confidential</span>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.querySelector('.print-button');
            if (btn) btn.onclick = function() { window.print(); };
        });
    </script>
</body>
</html>
