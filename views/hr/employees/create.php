<!-- Create/Add Employee Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('hr/employees') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Employees
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Employee</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('hr/employees/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        <i class="fas fa-user text-gray-300 text-4xl"></i>
                    </div>
                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <label for="photo" class="mt-2 inline-flex items-center text-sm text-gold-500 hover:text-gold-600 cursor-pointer">
                        <i class="fas fa-camera mr-1"></i>Upload Photo
                    </label>
                </div>
                
                <!-- Name Fields -->
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="<?= old('email') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" value="<?= old('phone') ?>"
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
                    <input type="text" name="employee_code" value="<?= e($employeeCode ?? '') ?>" readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="position" value="<?= old('position') ?>" placeholder="e.g., Software Developer"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" value="<?= old('department') ?>" placeholder="e.g., Engineering"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                    <input type="date" name="hire_date" value="<?= old('hire_date', date('Y-m-d')) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                    <select name="employment_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="intern">Intern</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
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
                    <input type="number" name="salary" value="<?= old('salary') ?>" step="0.01" min="0" placeholder="0.00"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Frequency</label>
                    <select name="pay_frequency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="monthly">Monthly</option>
                        <option value="bi_weekly">Bi-Weekly</option>
                        <option value="weekly">Weekly</option>
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
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= old('emergency_contact') ?></textarea>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('hr/employees') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-user-plus mr-2"></i>Add Employee
            </button>
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
