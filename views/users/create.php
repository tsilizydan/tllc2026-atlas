<!-- Create User Form -->

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('users') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Users
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New User</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('users/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Profile Photo -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-camera mr-2 text-gold-500"></i>Profile Photo
            </h2>
            
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden" id="avatarPreview">
                    <i class="fas fa-user text-gray-300 text-3xl"></i>
                </div>
                <div>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    <label for="avatar" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 cursor-pointer transition">
                        <i class="fas fa-upload mr-2"></i>Upload Photo
                    </label>
                    <p class="text-xs text-gray-400 mt-1">JPEG, PNG up to 2MB</p>
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
                    <input type="text" name="first_name" value="<?= old('first_name') ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="<?= old('last_name') ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?= old('email') ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="<?= old('username') ?>" placeholder="Auto-generated if empty"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    <p class="text-xs text-gray-400 mt-1">Minimum 8 characters</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>
        
        <!-- Role & Permissions -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-shield-alt mr-2 text-gold-500"></i>Role & Permissions
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Role</option>
                        <?php foreach ($roles ?? [] as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>>
                            <?= e($role['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex items-center pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Account is active</label>
                </div>
            </div>
            
            <!-- Role Permissions Info -->
            <div class="mt-4 p-4 bg-gray-50 rounded-lg" x-data="{showInfo: false}">
                <button type="button" @click="showInfo = !showInfo" class="text-sm text-gold-500 hover:text-gold-600">
                    <i class="fas fa-info-circle mr-1"></i>View Role Permissions
                </button>
                <div x-show="showInfo" class="mt-3 text-sm text-gray-600">
                    <p class="font-medium mb-2">Role Capabilities:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Admin:</strong> Full system access, user management, settings</li>
                        <li><strong>Manager:</strong> Project, client, and employee management</li>
                        <li><strong>Employee:</strong> View projects, tasks, personal info</li>
                        <li><strong>Client:</strong> View assigned projects and invoices</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle mr-2 text-gold-500"></i>Additional Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" value="<?= old('phone') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Department</option>
                        <option value="management">Management</option>
                        <option value="development">Development</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                        <option value="sales">Sales</option>
                        <option value="support">Support</option>
                        <option value="hr">Human Resources</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex items-center">
                <input type="checkbox" name="send_welcome_email" id="send_welcome_email" value="1" checked
                    class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500">
                <label for="send_welcome_email" class="ml-2 text-sm text-gray-700">Send welcome email with login credentials</label>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('users') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-user-plus mr-2"></i>Create User
            </button>
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
</script>
