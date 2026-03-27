<!-- Edit Employee Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('hr/employees/view?id=' . $employee['id']) ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Employee
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Employee</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('hr/employees/update?id=' . $employee['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Photo & Basic Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user mr-2 text-gold-500"></i>Personal Information
            </h2>
            
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Photo Upload -->
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 overflow-hidden" id="photoPreview">
                        <?php if (!empty($employee['photo'])): ?>
                        <img src="<?= upload($employee['photo']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                        <span class="text-3xl font-bold text-gray-300">
                            <?= strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <label for="photo" class="mt-2 inline-flex items-center text-sm text-gold-500 hover:text-gold-600 cursor-pointer">
                        <i class="fas fa-camera mr-1"></i>Change Photo
                    </label>
                </div>
                
                <!-- Name Fields -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="<?= e($employee['first_name']) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="<?= e($employee['last_name']) ?>" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="<?= e($employee['email'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" value="<?= e($employee['phone'] ?? '') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Employment Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-briefcase mr-2 text-gold-500"></i>Employment Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee Code</label>
                    <input type="text" value="<?= e($employee['employee_code'] ?? '') ?>" readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="position" value="<?= e($employee['position'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" value="<?= e($employee['department'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                    <input type="date" name="hire_date" value="<?= e($employee['hire_date'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                    <select name="employment_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="full_time" <?= ($employee['employment_type'] ?? '') === 'full_time' ? 'selected' : '' ?>>Full Time</option>
                        <option value="part_time" <?= ($employee['employment_type'] ?? '') === 'part_time' ? 'selected' : '' ?>>Part Time</option>
                        <option value="contract" <?= ($employee['employment_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contract</option>
                        <option value="intern" <?= ($employee['employment_type'] ?? '') === 'intern' ? 'selected' : '' ?>>Intern</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="active" <?= ($employee['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($employee['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Compensation -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-dollar-sign mr-2 text-gold-500"></i>Compensation
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Base Salary</label>
                    <input type="number" name="salary" value="<?= e($employee['salary'] ?? '') ?>" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Frequency</label>
                    <select name="pay_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="monthly" <?= ($employee['pay_frequency'] ?? '') === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                        <option value="bi_weekly" <?= ($employee['pay_frequency'] ?? '') === 'bi_weekly' ? 'selected' : '' ?>>Bi-Weekly</option>
                        <option value="weekly" <?= ($employee['pay_frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Emergency Contact -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-phone-alt mr-2 text-gold-500"></i>Emergency Contact
            </h2>
            
            <textarea name="emergency_contact" rows="3" placeholder="Name, relationship, and contact number..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($employee['emergency_contact'] ?? '') ?></textarea>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <form action="<?= url('hr/employees/delete?id=' . $employee['id']) ?>" method="POST" class="inline"
                onsubmit="return confirm('Are you sure you want to delete this employee? Paychecks will also be deleted.')">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete Employee
                </button>
            </form>
            
            <div class="flex items-center space-x-4">
                <a href="<?= url('hr/employees/view?id=' . $employee['id']) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
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
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
