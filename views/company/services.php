<!-- Company Services Management -->

<div class="max-w-4xl mx-auto" x-data="{ showAddForm: false }">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Company Profile</h1>
        <p class="text-gray-500 mt-1">Manage your company information and branding</p>
    </div>
    
    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <a href="<?= url('company/profile') ?>" class="py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Profile
            </a>
            <a href="<?= url('company/services') ?>" class="py-3 px-1 border-b-2 border-gold-500 text-gold-600 font-medium text-sm">
                Services
            </a>
            <a href="<?= url('company/branding') ?>" class="py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                Branding
            </a>
        </nav>
    </div>
    
    <!-- Add Service Button -->
    <div class="mb-6 flex items-center justify-between">
        <p class="text-gray-500">Manage the services your company offers</p>
        <button @click="showAddForm = !showAddForm" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas" :class="showAddForm ? 'fa-times' : 'fa-plus'"></i>
            <span x-text="showAddForm ? 'Cancel' : 'Add Service'"></span>
        </button>
    </div>
    
    <!-- Add Service Form -->
    <div x-show="showAddForm" x-transition class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Add New Service</h2>
        
        <form action="<?= url('company/services/store') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Starting Price</label>
                    <input type="number" name="price" step="0.01" min="0" placeholder="0.00"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                    <textarea name="short_description" rows="2" placeholder="Brief description..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"></textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                    <textarea name="description" rows="4" class="tinymce"
                        ></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Font Awesome class)</label>
                    <input type="text" name="icon" placeholder="fas fa-laptop-code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Service
                </button>
            </div>
        </form>
    </div>
    
    <!-- Services List -->
    <div class="space-y-4">
        <?php if (!empty($services)): ?>
        <?php foreach ($services as $service): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-gold-300 transition">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <?php if (!empty($service['image'])): ?>
                    <img src="<?= upload($service['image']) ?>" alt="<?= e($service['name']) ?>" class="w-16 h-16 rounded-lg object-cover">
                    <?php elseif (!empty($service['icon'])): ?>
                    <div class="w-16 h-16 bg-gold-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="<?= e($service['icon']) ?> text-gold-500 text-2xl"></i>
                    </div>
                    <?php else: ?>
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cog text-gray-400 text-2xl"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div>
                        <h3 class="font-semibold text-gray-900"><?= e($service['name']) ?></h3>
                        <?php if (!empty($service['short_description'])): ?>
                        <p class="text-sm text-gray-500 mt-1"><?= e($service['short_description']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($service['price'])): ?>
                        <p class="text-sm font-medium text-gold-500 mt-2">From <?= formatCurrency($service['price']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <?= statusBadge($service['is_active'] ? 'active' : 'inactive') ?>
                    
                    <a href="<?= url('company/services/edit?id=' . $service['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <form action="<?= url('company/services/delete?id=' . $service['id']) ?>" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this service?')">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php else: ?>
        <div class="bg-white rounded-xl border border-gray-200 text-center py-12">
            <i class="fas fa-cogs text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No services added yet</h3>
            <p class="text-gray-500 mb-4">Add the services your company offers to display them on invoices and proposals.</p>
            <button @click="showAddForm = true" class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-plus mr-2"></i>Add Your First Service
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>
