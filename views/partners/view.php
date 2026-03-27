<!-- Partner Detail View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <a href="<?= url('partners') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Partners
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= e($partner['company_name']) ?></h1>
        <p class="text-gray-500 mt-1"><?= ucfirst($partner['type'] ?? 'partner') ?> Partner</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('partners/edit?id=' . $partner['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Partner Profile -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start space-x-6">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <?php if (!empty($partner['logo'])): ?>
                    <img src="<?= upload($partner['logo'] ?? '', 'logo') ?>" alt="<?= e($partner['company_name']) ?>" 
                        class="w-24 h-24 rounded-xl object-contain border border-gray-200 p-2">
                    <?php else: ?>
                    <div class="w-24 h-24 rounded-xl bg-gold-50 flex items-center justify-center border border-gold-100">
                        <i class="fas fa-building text-gold-500 text-3xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Info -->
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <?= statusBadge($partner['status'] ?? 'active') ?>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-600">
                            <?= ucfirst($partner['type'] ?? 'strategic') ?>
                        </span>
                    </div>
                    
                    <?php if (!empty($partner['description'])): ?>
                    <p class="text-gray-600 mb-4"><?= e($partner['description']) ?></p>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <?php if (!empty($partner['website'])): ?>
                        <div>
                            <span class="text-gray-500">Website</span>
                            <p><a href="<?= e($partner['website']) ?>" target="_blank" class="text-gold-500 hover:underline"><?= e($partner['website']) ?></a></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($partner['city'])): ?>
                        <div>
                            <span class="text-gray-500">Location</span>
                            <p class="font-medium text-gray-900"><?= e($partner['city']) ?><?= !empty($partner['country']) ? ', ' . e($partner['country']) : '' ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Person -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user mr-2 text-gold-500"></i>Primary Contact
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (!empty($partner['contact_name'])): ?>
                <div>
                    <span class="text-sm text-gray-500">Name</span>
                    <p class="font-medium text-gray-900"><?= e($partner['contact_name']) ?></p>
                    <?php if (!empty($partner['contact_position'])): ?>
                    <p class="text-sm text-gray-500"><?= e($partner['contact_position']) ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($partner['email'])): ?>
                <div>
                    <span class="text-sm text-gray-500">Email</span>
                    <p><a href="mailto:<?= e($partner['email']) ?>" class="text-gold-500 hover:underline"><?= e($partner['email']) ?></a></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($partner['phone'])): ?>
                <div>
                    <span class="text-sm text-gray-500">Phone</span>
                    <p><a href="tel:<?= e($partner['phone']) ?>" class="text-gold-500 hover:underline"><?= e($partner['phone']) ?></a></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($partner['address'])): ?>
                <div class="md:col-span-2">
                    <span class="text-sm text-gray-500">Address</span>
                    <p class="text-gray-900"><?= e($partner['address']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Related Contracts -->
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Related Contracts</h2>
                <a href="<?= url('contracts/create?partner_id=' . $partner['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                    <i class="fas fa-plus mr-1"></i>Add Contract
                </a>
            </div>
            
            <?php if (!empty($contracts)): ?>
            <div class="divide-y divide-gray-100">
                <?php foreach ($contracts as $contract): ?>
                <a href="<?= url('contracts/view?id=' . $contract['id']) ?>" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-gray-900"><?= e($contract['title']) ?></p>
                        <p class="text-sm text-gray-500"><?= formatDate($contract['start_date']) ?> - <?= formatDate($contract['end_date']) ?></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <?= statusBadge($contract['status']) ?>
                        <span class="font-medium text-gray-900"><?= formatCurrency($contract['value'] ?? 0) ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-file-contract text-3xl mb-2 opacity-50"></i>
                <p>No contracts with this partner</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Partnership Stats</h3>
            
            <div class="space-y-4">
                <div class="text-center p-4 bg-gold-50 rounded-lg">
                    <p class="text-sm text-gray-500">Total Contract Value</p>
                    <p class="text-2xl font-bold text-gold-500"><?= formatCurrency($stats['total_value'] ?? 0) ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['active_contracts'] ?? 0 ?></p>
                        <p class="text-xs text-gray-500">Active</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900"><?= $stats['total_contracts'] ?? 0 ?></p>
                        <p class="text-xs text-gray-500">Total</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Quick Actions</h3>
            
            <div class="space-y-2">
                <a href="<?= url('contracts/create?partner_id=' . $partner['id']) ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-file-contract text-gold-500 w-6"></i>
                    <span class="text-gray-700">Create Contract</span>
                </a>
                <a href="<?= url('partners/edit?id=' . $partner['id']) ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-edit text-blue-500 w-6"></i>
                    <span class="text-gray-700">Edit Partner</span>
                </a>
                <?php if (!empty($partner['email'])): ?>
                <a href="mailto:<?= e($partner['email']) ?>" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition text-sm">
                    <i class="fas fa-envelope text-purple-500 w-6"></i>
                    <span class="text-gray-700">Send Email</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Notes -->
        <?php if (!empty($partner['notes'])): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-3">Internal Notes</h3>
            <p class="text-sm text-gray-600 whitespace-pre-line"><?= e($partner['notes']) ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Meta -->
        <div class="text-xs text-gray-400 space-y-1">
            <p>Added: <?= formatDateTime($partner['created_at'] ?? '') ?></p>
            <?php if (!empty($partner['updated_at'])): ?>
            <p>Updated: <?= formatDateTime($partner['updated_at']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
