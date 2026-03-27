<!-- Company Configuration Dashboard -->

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Company Configuration</h1>
        <p class="text-gray-500 mt-1">Manage your company settings and branding</p>
    </div>
    
    <!-- Config Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Profile Card -->
        <a href="<?= url('company/profile') ?>" class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i class="fas fa-building text-blue-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Company Profile</h3>
            <p class="text-gray-500 text-sm">Update company details, contact info, and address</p>
        </a>
        
        <!-- Services Card -->
        <a href="<?= url('company/services') ?>" class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition group">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i class="fas fa-concierge-bell text-green-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Services</h3>
            <p class="text-gray-500 text-sm">Manage your service offerings and pricing</p>
            <div class="mt-3">
                <span class="text-sm text-gray-400"><?= count($services ?? []) ?> services</span>
            </div>
        </a>
        
        <!-- Branding Card -->
        <a href="<?= url('company/branding') ?>" class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition group">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i class="fas fa-palette text-purple-500 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Branding</h3>
            <p class="text-gray-500 text-sm">Customize colors, logo, and visual identity</p>
        </a>
    </div>
    
    <!-- Company Overview -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-info-circle mr-2 text-gold-500"></i>Company Overview
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-center">
                    <?php if (!empty($company['logo'])): ?>
                    <img src="<?= upload($company['logo'] ?? $company['logo_path'] ?? '', 'logo') ?>" alt="Logo" class="w-16 h-16 rounded-lg object-contain bg-gray-50">
                    <?php else: ?>
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-gray-400 text-2xl"></i>
                    </div>
                    <?php endif; ?>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900"><?= e($company['company_name'] ?? 'TSILIZY LLC') ?></h3>
                        <p class="text-sm text-gray-500"><?= e($company['tagline'] ?? '') ?></p>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <?php if (!empty($company['email'])): ?>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span class="ml-2"><?= e($company['email']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($company['phone'])): ?>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span class="ml-2"><?= e($company['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($company['website'])): ?>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-globe w-5 text-gray-400"></i>
                        <a href="<?= e($company['website']) ?>" target="_blank" class="ml-2 text-gold-500 hover:underline"><?= e($company['website']) ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-2 text-sm">
                <?php if (!empty($company['address'])): ?>
                <div class="flex items-start text-gray-600">
                    <i class="fas fa-map-marker-alt w-5 text-gray-400 mt-0.5"></i>
                    <span class="ml-2">
                        <?= e($company['address']) ?><br>
                        <?= e(($company['city'] ?? '') . ', ' . ($company['country'] ?? '')) ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($company['registration_number'])): ?>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-id-card w-5 text-gray-400"></i>
                    <span class="ml-2">Reg: <?= e($company['registration_number']) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($company['tax_id'])): ?>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-file-invoice w-5 text-gray-400"></i>
                    <span class="ml-2">Tax ID: <?= e($company['tax_id']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
