<!-- User Profile -->

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gold-500 h-32 relative"></div>
        
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <!-- Avatar -->
                <div class="relative">
                    <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= upload($user['avatar']) ?>" class="w-32 h-32 rounded-xl border-4 border-white object-cover shadow-sm">
                    <?php else: ?>
                    <div class="w-32 h-32 rounded-xl border-4 border-white bg-gray-200 flex items-center justify-center shadow-sm">
                        <span class="text-4xl text-gray-500 font-bold"><?= strtoupper(substr($user['first_name'], 0, 1)) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Role Badge -->
                <span class="px-4 py-1.5 bg-gray-900 text-white rounded-full text-sm font-medium shadow-sm">
                    <?= e($user['role_name']) ?>
                </span>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-1"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></h1>
            <p class="text-gray-500 mb-6"><?= e($user['email']) ?></p>
            
            <form action="<?= url('users/profile/update') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" value="<?= e($user['first_name']) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="<?= e($user['last_name']) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="<?= e($user['email']) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div class="md:col-span-2 border-t border-gray-100 pt-6 mt-2">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Security</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password (Optional)</label>
                        <input type="password" name="password" minlength="8"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div class="md:col-span-2">
                         <label class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                         <input type="file" name="avatar" accept="image/*"
                             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                </div>
                
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 bg-gold-500 text-charcoal font-bold rounded-lg hover:bg-gold-600 transition shadow-md">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
