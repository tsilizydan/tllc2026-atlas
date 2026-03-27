<!-- Edit Service Form -->

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('company/services') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Services
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Service</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('company/services/update?id=' . ($service['id'] ?? '')) ?>" method="POST" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        <input type="hidden" name="id" value="<?= $service['id'] ?? '' ?>">
        
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?= e($service['name'] ?? '') ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class</label>
                <input type="text" name="icon" value="<?= e($service['icon'] ?? 'fas fa-star') ?>" placeholder="e.g., fas fa-laptop-code"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <p class="mt-1 text-xs text-gray-500">Use Font Awesome classes like "fas fa-code"</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                <input type="text" name="price_range" value="<?= e($service['price_range'] ?? '') ?>" placeholder="e.g., $500 - $5,000"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Describe this service..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($service['description'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?= e($service['sort_order'] ?? 0) ?>" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" <?= ($service['is_active'] ?? 1) ? 'checked' : '' ?>
                    class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('company/services') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-save mr-2"></i>Update Service
            </button>
        </div>
    </form>
</div>
