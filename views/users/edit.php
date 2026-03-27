<!-- Edit User Form -->

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('users') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('users/update?id=' . $user['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Profile Photo -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-camera mr-2 text-gold-500"></i>Profile Photo
            </h2>
            
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden" id="avatarPreview">
                    <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= upload($user['avatar']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                    <span class="text-gold-600 font-medium text-2xl">
                        <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    <label for="avatar" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 cursor-pointer transition">
                        <i class="fas fa-upload mr-2"></i>Change Photo
                    </label>
                    <?php if (!empty($user['avatar'])): ?>
                    <button type="button" onclick="removeAvatar()" class="ml-2 text-sm text-red-500 hover:text-red-700">Remove</button>
                    <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Account Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user-circle mr-2 text-gold-500"></i>Account Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="<?= e($user['first_name']) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="<?= e($user['last_name']) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?= e($user['email']) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="<?= e($user['username'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Department</option>
                        <?php 
                        $departments = ['management', 'development', 'design', 'marketing', 'sales', 'support', 'hr', 'finance'];
                        foreach ($departments as $dept): 
                        ?>
                        <option value="<?= $dept ?>" <?= ($user['department'] ?? '') === $dept ? 'selected' : '' ?>>
                            <?= ucfirst($dept) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Role & Status -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-shield-alt mr-2 text-gold-500"></i>Role & Status
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
                        <?= $user['id'] === Auth::id() ? 'disabled' : '' ?>>
                        <?php foreach ($roles ?? [] as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= ($user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                            <?= e($role['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($user['id'] === Auth::id()): ?>
                    <input type="hidden" name="role_id" value="<?= $user['role_id'] ?>">
                    <p class="text-xs text-gray-400 mt-1">You cannot change your own role</p>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?>
                        class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500"
                        <?= $user['id'] === Auth::id() ? 'disabled' : '' ?>>
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Account is active</label>
                    <?php if ($user['id'] === Auth::id()): ?>
                    <input type="hidden" name="is_active" value="1">
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Last Activity -->
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Last Login:</span>
                        <span class="text-gray-900"><?= $user['last_login'] ? formatDateTime($user['last_login']) : 'Never' ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="text-gray-900"><?= formatDateTime($user['created_at']) ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Password -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-lock mr-2 text-gold-500"></i>Change Password
            </h2>
            <p class="text-sm text-gray-500 mb-4">Leave blank to keep current password</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    <p class="text-xs text-gray-400 mt-1">Minimum 8 characters</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <?php if ($user['id'] !== Auth::id()): ?>
            <form action="<?= url('users/delete?id=' . $user['id']) ?>" method="POST" class="inline"
                onsubmit="return confirm('Are you sure you want to delete this user?')">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete User
                </button>
            </form>
            <?php else: ?>
            <div></div>
            <?php endif; ?>
            
            <div class="flex items-center space-x-4">
                <a href="<?= url('users') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
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
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeAvatar() {
    if (confirm('Remove profile photo?')) {
        document.getElementById('remove_avatar').value = '1';
        document.getElementById('avatarPreview').innerHTML = '<span class="text-gold-600 font-medium text-2xl"><?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?></span>';
    }
}
</script>
