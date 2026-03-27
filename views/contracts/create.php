<!-- Create Contract Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('contracts') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Contracts
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Create New Contract</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('contracts/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Basic Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-contract mr-2 text-gold-500"></i>Contract Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Number</label>
                    <input type="text" name="contract_number" value="<?= e($contractNumber ?? '') ?>" readonly
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="service">Service Agreement</option>
                        <option value="partnership">Partnership Agreement</option>
                        <option value="nda">Non-Disclosure Agreement</option>
                        <option value="employment">Employment Contract</option>
                        <option value="licensing">Licensing Agreement</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?= old('title') ?>" required placeholder="e.g., Website Development Agreement"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <select name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Client</option>
                        <?php foreach ($clients ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= old('client_id') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Or Partner</label>
                    <select name="partner_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Select Partner</option>
                        <?php foreach ($partners ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= old('partner_id') == $id ? 'selected' : '' ?>><?= e($name) ?></option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="<?= old('start_date', date('Y-m-d')) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" value="<?= old('end_date') ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contract Value</label>
                    <input type="number" name="value" value="<?= old('value') ?>" step="0.01" min="0" placeholder="0.00"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="draft">Draft</option>
                        <option value="active">Active</option>
                    </select>
                </div>
                
                <div class="flex items-center md:col-span-2 pt-6">
                    <input type="checkbox" name="auto_renew" id="auto_renew" value="1"
                        class="w-4 h-4 text-gold-500 border-gray-300 rounded focus:ring-gold-500">
                    <label for="auto_renew" class="ml-2 text-sm text-gray-700">Auto-renew at end of term</label>
                </div>
            </div>
        </div>
        
        <!-- Document Upload -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-upload mr-2 text-gold-500"></i>Contract Document
            </h2>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                <input type="file" name="document" id="document" accept=".pdf,.doc,.docx" class="hidden">
                <label for="document" class="cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600 mb-1">Click to upload contract document</p>
                    <p class="text-xs text-gray-400">PDF, DOC, DOCX up to 10MB</p>
                </label>
            </div>
        </div>
        
        <!-- Terms & Notes -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-align-left mr-2 text-gold-500"></i>Description & Notes
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="tinymce"><?= old('description') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                    <textarea name="notes" rows="3" placeholder="Internal notes (not shown on printed contract)..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"><?= old('notes') ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="<?= url('contracts') ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" name="action" value="draft" class="px-6 py-2 border border-gold-500 text-gold-600 rounded-lg hover:bg-gold-50 transition">
                <i class="fas fa-save mr-2"></i>Save as Draft
            </button>
            <button type="submit" name="action" value="active" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-check mr-2"></i>Create & Activate
            </button>
        </div>
    </form>
</div>
