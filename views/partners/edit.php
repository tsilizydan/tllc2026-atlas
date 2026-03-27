<!-- Edit Partner Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('partners/view?id=' . $partner['id']) ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Partner
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Partner</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('partners/update?id=' . $partner['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Logo & Company Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-handshake mr-2 text-gold-500"></i>Partner Information
            </h2>
            
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Logo Upload -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 overflow-hidden" id="logoPreview">
                        <?php if (!empty($partner['logo'])): ?>
                        <img src="<?= upload($partner['logo'] ?? '', 'logo') ?>" class="w-full h-full object-contain p-1">
                        <?php else: ?>
                        <i class="fas fa-building text-gray-300 text-3xl"></i>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                    <label for="logo" class="mt-2 inline-flex items-center text-sm text-gold-500 hover:text-gold-600 cursor-pointer">
                        <i class="fas fa-image mr-1"></i>Change Logo
                    </label>
                </div>
                
                <!-- Company Fields -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="<?= e($partner['company_name']) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Partner Type</label>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="strategic" <?= ($partner['type'] ?? '') === 'strategic' ? 'selected' : '' ?>>Strategic Partner</option>
                            <option value="affiliate" <?= ($partner['type'] ?? '') === 'affiliate' ? 'selected' : '' ?>>Affiliate</option>
                            <option value="vendor" <?= ($partner['type'] ?? '') === 'vendor' ? 'selected' : '' ?>>Vendor/Supplier</option>
                            <option value="referral" <?= ($partner['type'] ?? '') === 'referral' ? 'selected' : '' ?>>Referral Partner</option>
                            <option value="reseller" <?= ($partner['type'] ?? '') === 'reseller' ? 'selected' : '' ?>>Reseller</option>
                            <option value="other" <?= ($partner['type'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="active" <?= ($partner['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($partner['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($partner['description'] ?? '') ?></textarea>
            </div>
        </div>
        
        <!-- Contact Person -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user mr-2 text-gold-500"></i>Primary Contact
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                    <input type="text" name="contact_name" value="<?= e($partner['contact_name'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="contact_position" value="<?= e($partner['contact_position'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?= e($partner['email'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="<?= e($partner['phone'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>
        
        <!-- Address & Website -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-globe mr-2 text-gold-500"></i>Location & Online Presence
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" value="<?= e($partner['address'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="<?= e($partner['city'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" value="<?= e($partner['country'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="<?= e($partner['website'] ?? '') ?>" placeholder="https://"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-sticky-note mr-2 text-gold-500"></i>Internal Notes
            </h2>
            
            <textarea name="notes" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($partner['notes'] ?? '') ?></textarea>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <form action="<?= url('partners/delete?id=' . $partner['id']) ?>" method="POST" class="inline"
                onsubmit="return confirm('Are you sure you want to delete this partner?')">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete Partner
                </button>
            </form>
            
            <div class="flex items-center space-x-4">
                <a href="<?= url('partners/view?id=' . $partner['id']) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-contain p-1">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
