<!-- Company Branding Settings -->

<!-- Header with Tabs -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Company Settings</h1>
    <p class="text-gray-500 mt-1">Manage your company branding and appearance</p>
</div>

<!-- Settings Navigation -->
<div class="bg-white rounded-xl border border-gray-200 mb-6">
    <div class="flex overflow-x-auto">
        <a href="<?= url('company/profile') ?>" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-900 whitespace-nowrap border-b-2 border-transparent">
            Profile
        </a>
        <a href="<?= url('company/services') ?>" class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-900 whitespace-nowrap border-b-2 border-transparent">
            Services
        </a>
        <a href="<?= url('company/branding') ?>" class="px-6 py-3 text-sm font-medium text-gold-500 whitespace-nowrap border-b-2 border-gold-500">
            Branding
        </a>
    </div>
</div>

<!-- Branding Form -->
<form action="<?= url('company/branding/update') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
    
    <!-- Logos -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-image mr-2 text-gold-500"></i>Logos
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Logo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Logo</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center" id="mainLogoPreview">
                    <?php if (!empty($branding['logo'])): ?>
                    <img src="<?= upload($branding['logo'] ?? '', 'logo') ?>" class="max-h-20 mx-auto mb-2">
                    <?php else: ?>
                    <i class="fas fa-image text-gray-300 text-3xl mb-2"></i>
                    <?php endif; ?>
                    <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewImage(this, 'mainLogoPreview')">
                    <label for="logo" class="text-sm text-gold-500 hover:text-gold-600 cursor-pointer">
                        <i class="fas fa-upload mr-1"></i>Upload
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-1">Recommended: 200x60px PNG</p>
            </div>
            
            <!-- Dark Logo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dark Logo (for light backgrounds)</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center" id="darkLogoPreview">
                    <?php if (!empty($branding['logo_dark'])): ?>
                    <img src="<?= upload($branding['logo_dark'] ?? '', 'logo') ?>" class="max-h-20 mx-auto mb-2">
                    <?php else: ?>
                    <i class="fas fa-image text-gray-300 text-3xl mb-2"></i>
                    <?php endif; ?>
                    <input type="file" name="logo_dark" id="logo_dark" accept="image/*" class="hidden" onchange="previewImage(this, 'darkLogoPreview')">
                    <label for="logo_dark" class="text-sm text-gold-500 hover:text-gold-600 cursor-pointer">
                        <i class="fas fa-upload mr-1"></i>Upload
                    </label>
                </div>
            </div>
            
            <!-- Favicon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center" id="faviconPreview">
                    <?php if (!empty($branding['favicon'])): ?>
                    <img src="<?= upload($branding['favicon'] ?? '', 'logo') ?>" class="w-8 h-8 mx-auto mb-2">
                    <?php else: ?>
                    <i class="fas fa-star text-gray-300 text-2xl mb-2"></i>
                    <?php endif; ?>
                    <input type="file" name="favicon" id="favicon" accept="image/*,.ico" class="hidden" onchange="previewImage(this, 'faviconPreview')">
                    <label for="favicon" class="text-sm text-gold-500 hover:text-gold-600 cursor-pointer">
                        <i class="fas fa-upload mr-1"></i>Upload
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-1">32x32px PNG or ICO</p>
            </div>
        </div>
    </div>
    
    <!-- Colors -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-palette mr-2 text-gold-500"></i>Brand Colors
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="primary_color" value="<?= e($branding['primary_color'] ?? '#D4AF37') ?>"
                        class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <input type="text" value="<?= e($branding['primary_color'] ?? '#D4AF37') ?>" readonly
                        class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="secondary_color" value="<?= e($branding['secondary_color'] ?? '#2D2D2D') ?>"
                        class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <input type="text" value="<?= e($branding['secondary_color'] ?? '#2D2D2D') ?>" readonly
                        class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="accent_color" value="<?= e($branding['accent_color'] ?? '#10B981') ?>"
                        class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <input type="text" value="<?= e($branding['accent_color'] ?? '#10B981') ?>" readonly
                        class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="text_color" value="<?= e($branding['text_color'] ?? '#1F2937') ?>"
                        class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <input type="text" value="<?= e($branding['text_color'] ?? '#1F2937') ?>" readonly
                        class="flex-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
        </div>
        
        <!-- Color Preview -->
        <div class="mt-6 p-4 border border-gray-200 rounded-lg">
            <p class="text-sm text-gray-500 mb-3">Preview</p>
            <div class="flex items-center space-x-4">
                <button type="button" class="px-4 py-2 rounded-lg text-white" style="background-color: <?= e($branding['primary_color'] ?? '#D4AF37') ?>">
                    Primary Button
                </button>
                <button type="button" class="px-4 py-2 rounded-lg text-white" style="background-color: <?= e($branding['secondary_color'] ?? '#2D2D2D') ?>">
                    Secondary
                </button>
                <button type="button" class="px-4 py-2 rounded-lg text-white" style="background-color: <?= e($branding['accent_color'] ?? '#10B981') ?>">
                    Accent
                </button>
            </div>
        </div>
    </div>
    
    <!-- Typography -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-font mr-2 text-gold-500"></i>Typography
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Heading Font</label>
                <select name="heading_font" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    <option value="Inter" <?= ($branding['heading_font'] ?? '') === 'Inter' ? 'selected' : '' ?>>Inter</option>
                    <option value="Poppins" <?= ($branding['heading_font'] ?? '') === 'Poppins' ? 'selected' : '' ?>>Poppins</option>
                    <option value="Roboto" <?= ($branding['heading_font'] ?? '') === 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                    <option value="Playfair Display" <?= ($branding['heading_font'] ?? '') === 'Playfair Display' ? 'selected' : '' ?>>Playfair Display</option>
                    <option value="Montserrat" <?= ($branding['heading_font'] ?? '') === 'Montserrat' ? 'selected' : '' ?>>Montserrat</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Body Font</label>
                <select name="body_font" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    <option value="Inter" <?= ($branding['body_font'] ?? '') === 'Inter' ? 'selected' : '' ?>>Inter</option>
                    <option value="Open Sans" <?= ($branding['body_font'] ?? '') === 'Open Sans' ? 'selected' : '' ?>>Open Sans</option>
                    <option value="Roboto" <?= ($branding['body_font'] ?? '') === 'Roboto' ? 'selected' : '' ?>>Roboto</option>
                    <option value="Lato" <?= ($branding['body_font'] ?? '') === 'Lato' ? 'selected' : '' ?>>Lato</option>
                    <option value="Source Sans Pro" <?= ($branding['body_font'] ?? '') === 'Source Sans Pro' ? 'selected' : '' ?>>Source Sans Pro</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Invoice Branding -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-file-invoice mr-2 text-gold-500"></i>Invoice Branding
        </h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Header Color</label>
                <div class="flex items-center space-x-3">
                    <input type="color" name="invoice_header_color" value="<?= e($branding['invoice_header_color'] ?? '#2D2D2D') ?>"
                        class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer">
                    <span class="text-sm text-gray-500">Used in invoice header background</span>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Footer Text</label>
                <textarea name="invoice_footer" rows="2" placeholder="Thank you for your business!"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($branding['invoice_footer'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="flex items-center justify-end space-x-4">
        <button type="button" onclick="resetToDefaults()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            Reset to Defaults
        </button>
        <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-save mr-2"></i>Save Branding
        </button>
    </div>
</form>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.innerHTML = '<img src="' + e.target.result + '" class="max-h-20 mx-auto mb-2"><label for="' + input.id + '" class="text-sm text-gold-500 hover:text-gold-600 cursor-pointer"><i class="fas fa-upload mr-1"></i>Change</label>';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all branding to default values?')) {
        // Reset color inputs
        document.querySelector('input[name="primary_color"]').value = '#D4AF37';
        document.querySelector('input[name="secondary_color"]').value = '#2D2D2D';
        document.querySelector('input[name="accent_color"]').value = '#10B981';
        document.querySelector('input[name="text_color"]').value = '#1F2937';
    }
}

// Sync color input with text display
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });
});
</script>
