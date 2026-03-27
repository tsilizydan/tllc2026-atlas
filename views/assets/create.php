<!-- Add Asset Form -->

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?= url('assets') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Assets
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add Asset</h1>
    </div>

    <form action="<?= url('assets/store') ?>" method="POST" class="space-y-8">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-box mr-2 text-gold-500"></i>Asset Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asset Tag <span class="text-red-500">*</span></label>
                    <input type="text" name="asset_tag" value="<?= e(old('asset_tag', $assetTag ?? '')) ?>" required readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Category</option>
                        <?php foreach ($categories ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= old('category_id') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asset Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= e(old('name')) ?>" required placeholder="e.g., Dell Laptop XPS 15"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                    <input type="text" name="serial_number" value="<?= e(old('serial_number')) ?>" placeholder="e.g., SN123456"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="<?= e(old('location')) ?>" placeholder="e.g., Office A, Floor 2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Asset description..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e(old('description')) ?></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-dollar-sign mr-2 text-gold-500"></i>Purchase & Warranty
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
                    <input type="date" name="purchase_date" value="<?= e(old('purchase_date')) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Price</label>
                    <input type="number" name="purchase_price" value="<?= e(old('purchase_price')) ?>" step="0.01" min="0" placeholder="0.00"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warranty Expiry</label>
                    <input type="date" name="warranty_expiry" value="<?= e(old('warranty_expiry')) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-user mr-2 text-gold-500"></i>Assignment
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Employee</label>
                    <select name="employee_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Unassigned (Available)</option>
                        <?php foreach ($employees ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= old('employee_id') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="available">Available</option>
                        <option value="in_repair">In Repair</option>
                        <option value="retired">Retired</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" placeholder="Internal notes..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e(old('notes')) ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('assets') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-plus mr-2"></i>Add Asset
            </button>
        </div>
    </form>
</div>
