<!-- Services Management -->

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center space-x-2 mb-2">
                <a href="<?= url('company') ?>" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-chevron-left mr-1"></i>Company
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Services</h1>
            <p class="text-gray-500 mt-1">Manage your service offerings</p>
        </div>
        
        <a href="<?= url('company/services/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add Service
        </a>
    </div>
    
    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gold-100 rounded-lg flex items-center justify-center">
                        <i class="<?= e($service['icon'] ?? 'fas fa-star') ?> text-gold-500 text-xl"></i>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="<?= url('company/services/edit?id=' . $service['id']) ?>" class="p-2 text-gray-400 hover:text-gold-500">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?= url('company/services/delete') ?>" method="POST" class="inline" onsubmit="return confirm('Delete this service?')">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                            <input type="hidden" name="id" value="<?= $service['id'] ?>">
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <h3 class="font-semibold text-gray-900 mb-2"><?= e($service['name'] ?? '') ?></h3>
                <p class="text-sm text-gray-500 mb-4 line-clamp-2"><?= e($service['description'] ?? '') ?></p>
                
                <div class="flex items-center justify-between text-sm">
                    <?php if (!empty($service['price_range'])): ?>
                    <span class="text-gray-600"><?= e($service['price_range']) ?></span>
                    <?php endif; ?>
                    <span class="px-2 py-1 rounded-full text-xs <?= ($service['is_active'] ?? 1) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                        <?= ($service['is_active'] ?? 1) ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-200">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-concierge-bell text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Services Yet</h3>
                <p class="text-gray-500 mb-4">Add your first service to get started</p>
                <a href="<?= url('company/services/create') ?>" class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-plus mr-2"></i>Add Service
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
