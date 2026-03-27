<!-- Contract Detail View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('contracts') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Contracts
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= e($contract['title']) ?></h1>
        <p class="text-gray-500 mt-1"><?= e($contract['contract_number']) ?></p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <?php if (!empty($contract['document'])): ?>
        <a href="<?= upload($contract['document']) ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-download mr-2"></i>Download
        </a>
        <?php endif; ?>
        <a href="<?= url('contracts/edit?id=' . $contract['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Overview Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Contract Details</h2>
                <?= statusBadge($contract['status']) ?>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Type</p>
                    <p class="font-medium text-gray-900"><?= ucfirst($contract['type'] ?? 'service') ?></p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Start Date</p>
                    <p class="font-medium text-gray-900"><?= formatDate($contract['start_date']) ?></p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">End Date</p>
                    <p class="font-medium text-gray-900"><?= formatDate($contract['end_date']) ?></p>
                </div>
                <div class="text-center p-3 bg-gold-50 rounded-lg">
                    <p class="text-sm text-gray-500">Value</p>
                    <p class="font-bold text-gold-500"><?= formatCurrency($contract['value'] ?? 0) ?></p>
                </div>
            </div>
            
            <?php if (!empty($contract['description'])): ?>
            <div class="prose max-w-none">
                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-2">Description</h3>
                <div class="text-gray-600"><?= $contract['description'] ?></div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Related Party -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <?php if (!empty($contract['client_id'])): ?>
                <i class="fas fa-building mr-2 text-gold-500"></i>Client
                <?php else: ?>
                <i class="fas fa-handshake mr-2 text-gold-500"></i>Partner
                <?php endif; ?>
            </h2>
            
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-gold-500"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900"><?= e($contract['company_name'] ?? $contract['partner_name'] ?? 'N/A') ?></p>
                    <?php if (!empty($contract['contact_email'])): ?>
                    <p class="text-sm text-gray-500"><?= e($contract['contact_email']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <?php if (!empty($contract['notes'])): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 uppercase mb-3">Internal Notes</h2>
            <p class="text-gray-600 whitespace-pre-line"><?= e($contract['notes']) ?></p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Actions -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Actions</h3>
            
            <div class="space-y-2">
                <?php if ($contract['status'] === 'draft'): ?>
                <form action="<?= url('contracts/update-status?id=' . $contract['id']) ?>" method="POST">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="w-full flex items-center justify-center p-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-check mr-2"></i>Activate Contract
                    </button>
                </form>
                <?php endif; ?>
                
                <?php if ($contract['status'] === 'active'): ?>
                <form action="<?= url('contracts/update-status?id=' . $contract['id']) ?>" method="POST">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="w-full flex items-center justify-center p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-check-double mr-2"></i>Mark Completed
                    </button>
                </form>
                
                <form action="<?= url('contracts/update-status?id=' . $contract['id']) ?>" method="POST"
                    onsubmit="return confirm('Are you sure you want to terminate this contract?')">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                    <input type="hidden" name="status" value="terminated">
                    <button type="submit" class="w-full flex items-center justify-center p-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-times mr-2"></i>Terminate
                    </button>
                </form>
                <?php endif; ?>
                
                <a href="<?= url('contracts/edit?id=' . $contract['id']) ?>" class="flex items-center justify-center p-3 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-edit mr-2"></i>Edit Contract
                </a>
            </div>
        </div>
        
        <!-- Timeline Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Timeline</h3>
            
            <?php
            $startDate = strtotime($contract['start_date']);
            $endDate = strtotime($contract['end_date']);
            $today = time();
            $totalDays = ($endDate - $startDate) / 86400;
            $elapsedDays = ($today - $startDate) / 86400;
            $progress = min(100, max(0, ($elapsedDays / $totalDays) * 100));
            $daysRemaining = max(0, floor(($endDate - $today) / 86400));
            ?>
            
            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Progress</span>
                    <span><?= round($progress) ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gold-500 h-2 rounded-full" style="width: <?= $progress ?>%"></div>
                </div>
            </div>
            
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold <?= $daysRemaining <= 30 ? 'text-red-500' : 'text-gray-900' ?>">
                    <?= $daysRemaining ?>
                </p>
                <p class="text-sm text-gray-500">Days Remaining</p>
            </div>
            
            <?php if (!empty($contract['auto_renew'])): ?>
            <div class="mt-3 text-center">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">
                    <i class="fas fa-sync mr-1"></i>Auto-Renew Enabled
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Attached Document -->
        <?php if (!empty($contract['document'])): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Document</h3>
            
            <a href="<?= upload($contract['document']) ?>" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate"><?= basename($contract['document']) ?></p>
                    <p class="text-xs text-gray-500">Click to download</p>
                </div>
                <i class="fas fa-download text-gray-400"></i>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Meta -->
        <div class="text-xs text-gray-400 space-y-1">
            <p>Created: <?= formatDateTime($contract['created_at'] ?? '') ?></p>
            <?php if (!empty($contract['updated_at'])): ?>
            <p>Updated: <?= formatDateTime($contract['updated_at']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
