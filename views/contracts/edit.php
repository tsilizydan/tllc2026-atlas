<!-- Edit Contract Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('contracts/view?id=' . $contract['id']) ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Contract
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Contract</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('contracts/update?id=' . $contract['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Basic Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-contract mr-2 text-gold-500"></i>Contract Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Number</label>
                    <input type="text" value="<?= e($contract['contract_number']) ?>" readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="service" <?= ($contract['type'] ?? '') === 'service' ? 'selected' : '' ?>>Service Agreement</option>
                        <option value="partnership" <?= ($contract['type'] ?? '') === 'partnership' ? 'selected' : '' ?>>Partnership Agreement</option>
                        <option value="nda" <?= ($contract['type'] ?? '') === 'nda' ? 'selected' : '' ?>>Non-Disclosure Agreement</option>
                        <option value="employment" <?= ($contract['type'] ?? '') === 'employment' ? 'selected' : '' ?>>Employment Contract</option>
                        <option value="licensing" <?= ($contract['type'] ?? '') === 'licensing' ? 'selected' : '' ?>>Licensing Agreement</option>
                        <option value="other" <?= ($contract['type'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?= e($contract['title']) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <select name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">No Client</option>
                        <?php foreach ($clients ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= ($contract['client_id'] ?? '') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partner</label>
                    <select name="partner_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">No Partner</option>
                        <?php foreach ($partners ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= ($contract['partner_id'] ?? '') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Timeline & Value -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-gold-500"></i>Timeline & Value
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="<?= e($contract['start_date'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="<?= e($contract['end_date'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Value</label>
                    <input type="number" name="value" value="<?= e($contract['value'] ?? '') ?>" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="draft" <?= ($contract['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= ($contract['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="expired" <?= ($contract['status'] ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
                        <option value="terminated" <?= ($contract['status'] ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                        <option value="completed" <?= ($contract['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                
                <div class="flex items-center md:col-span-2 pt-6">
                    <input type="checkbox" name="auto_renew" id="auto_renew" value="1" <?= !empty($contract['auto_renew']) ? 'checked' : '' ?>
                        class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500">
                    <label for="auto_renew" class="ml-2 text-sm text-gray-700">Auto-renew at end of term</label>
                </div>
            </div>
        </div>
        
        <!-- Document -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-upload mr-2 text-gold-500"></i>Contract Document
            </h2>
            
            <?php if (!empty($contract['document'])): ?>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-4">
                <div class="flex items-center">
                    <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900"><?= basename($contract['document']) ?></p>
                        <p class="text-xs text-gray-500">Current document</p>
                    </div>
                </div>
                <a href="<?= upload($contract['document']) ?>" target="_blank" class="text-gold-500 hover:text-gold-600">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <?php endif; ?>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <input type="file" name="document" id="document" accept=".pdf,.doc,.docx" class="hidden">
                <label for="document" class="cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-600 text-sm">Click to upload new document</p>
                    <p class="text-xs text-gray-400">PDF, DOC, DOCX up to 10MB</p>
                </label>
            </div>
        </div>
        
        <!-- Description & Notes -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-align-left mr-2 text-gold-500"></i>Description & Notes
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="tinymce"><?= e($contract['description'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= e($contract['notes'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <form action="<?= url('contracts/delete?id=' . $contract['id']) ?>" method="POST" class="inline"
                onsubmit="return confirm('Are you sure you want to delete this contract?')">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-trash mr-2"></i>Delete Contract
                </button>
            </form>
            
            <div class="flex items-center space-x-4">
                <a href="<?= url('contracts/view?id=' . $contract['id']) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
