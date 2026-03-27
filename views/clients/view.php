<!-- View Client -->

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <a href="<?= url('clients') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
                <i class="fas fa-arrow-left mr-2"></i>Back to Clients
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?= e($client['company_name']) ?></h1>
            <div class="flex items-center space-x-3 mt-2">
                <?= statusBadge($client['status'] ?? 'active') ?>
                <?php if (!empty($client['website'])): ?>
                <a href="<?= e($client['website']) ?>" target="_blank" class="text-sm text-blue-500 hover:underline">
                    <i class="fas fa-external-link-alt mr-1"></i><?= e($client['website']) ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <a href="<?= url('clients/print-profile?id=' . $client['id']) ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-print mr-2"></i>Print
            </a>
            <a href="<?= url('clients/edit?id=' . $client['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>
    
    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Details -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Contact Info -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                
                <div class="space-y-4">
                    <?php if (!empty($client['contact_name'])): ?>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-user text-gray-400 w-5 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Contact Person</p>
                            <p class="text-sm text-gray-900"><?= e($client['contact_name']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($client['email'])): ?>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-envelope text-gray-400 w-5 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Email</p>
                            <a href="mailto:<?= e($client['email']) ?>" class="text-sm text-blue-500 hover:underline"><?= e($client['email']) ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($client['phone'])): ?>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-phone text-gray-400 w-5 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Phone</p>
                            <a href="tel:<?= e($client['phone']) ?>" class="text-sm text-gray-900"><?= e($client['phone']) ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($client['address'])): ?>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-gray-400 w-5 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Address</p>
                            <p class="text-sm text-gray-900">
                                <?= e($client['address']) ?>
                                <?php if (!empty($client['city'])): ?><br><?= e($client['city']) ?><?php endif; ?>
                                <?php if (!empty($client['country'])): ?>, <?= e($client['country']) ?><?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($client['tax_id'])): ?>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-id-card text-gray-400 w-5 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Tax ID</p>
                            <p class="text-sm text-gray-900"><?= e($client['tax_id']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Total Revenue</span>
                        <span class="text-lg font-bold text-green-600"><?= formatCurrency($client['total_revenue'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Projects</span>
                        <span class="text-lg font-bold text-gray-900"><?= count($client['projects'] ?? []) ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Invoices</span>
                        <span class="text-lg font-bold text-gray-900"><?= count($client['invoices'] ?? []) ?></span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Contracts</span>
                        <span class="text-lg font-bold text-gray-900"><?= count($client['contracts'] ?? []) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <?php if (!empty($client['notes'])): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-sm text-gray-600"><?= nl2br(e($client['notes'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Related Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Projects -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Projects</h2>
                    <a href="<?= url('projects/create?client_id=' . $client['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                        <i class="fas fa-plus mr-1"></i>New Project
                    </a>
                </div>
                
                <?php if (!empty($client['projects'])): ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach (array_slice($client['projects'], 0, 5) as $project): ?>
                    <a href="<?= url('projects/view?id=' . $project['id']) ?>" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div>
                            <p class="font-medium text-gray-900"><?= e($project['name']) ?></p>
                            <p class="text-sm text-gray-500"><?= formatDate($project['created_at']) ?></p>
                        </div>
                        <?= statusBadge($project['status']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-project-diagram text-3xl mb-2 opacity-50"></i>
                    <p>No projects yet</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Invoices -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Invoices</h2>
                    <a href="<?= url('invoices/create?client_id=' . $client['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                        <i class="fas fa-plus mr-1"></i>New Invoice
                    </a>
                </div>
                
                <?php if (!empty($client['invoices'])): ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach (array_slice($client['invoices'], 0, 5) as $invoice): ?>
                    <a href="<?= url('invoices/view?id=' . $invoice['id']) ?>" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div>
                            <p class="font-medium text-gray-900"><?= e($invoice['invoice_number']) ?></p>
                            <p class="text-sm text-gray-500"><?= formatDate($invoice['issue_date']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900"><?= formatCurrency($invoice['total']) ?></p>
                            <?= statusBadge($invoice['status']) ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-file-invoice-dollar text-3xl mb-2 opacity-50"></i>
                    <p>No invoices yet</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Contracts -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Contracts</h2>
                    <a href="<?= url('contracts/create?client_id=' . $client['id']) ?>" class="text-sm text-gold-500 hover:text-gold-600">
                        <i class="fas fa-plus mr-1"></i>New Contract
                    </a>
                </div>
                
                <?php if (!empty($client['contracts'])): ?>
                <div class="divide-y divide-gray-200">
                    <?php foreach (array_slice($client['contracts'], 0, 5) as $contract): ?>
                    <a href="<?= url('contracts/view?id=' . $contract['id']) ?>" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <div>
                            <p class="font-medium text-gray-900"><?= e($contract['title']) ?></p>
                            <p class="text-sm text-gray-500"><?= e($contract['contract_number']) ?></p>
                        </div>
                        <div class="text-right">
                            <?php if (!empty($contract['value'])): ?>
                            <p class="font-medium text-gray-900"><?= formatCurrency($contract['value']) ?></p>
                            <?php endif; ?>
                            <?= statusBadge($contract['status']) ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-file-contract text-3xl mb-2 opacity-50"></i>
                    <p>No contracts yet</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
