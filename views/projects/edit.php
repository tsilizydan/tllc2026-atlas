<!-- Edit Project Form -->

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?= url('projects/view?id=' . $project['id']) ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>Back to Project
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Project</h1>
    </div>
    
    <!-- Form -->
    <form action="<?= url('projects/update?id=' . $project['id']) ?>" method="POST" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
        
        <!-- Basic Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-project-diagram mr-2 text-gold-500"></i>Project Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= e($project['name']) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <select name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="">Internal Project</option>
                        <?php foreach ($clients ?? [] as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $project['client_id'] == $id ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="planning" <?= $project['status'] === 'planning' ? 'selected' : '' ?>>Planning</option>
                        <option value="active" <?= $project['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="on_hold" <?= $project['status'] === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                        <option value="completed" <?= $project['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $project['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        <option value="low" <?= $project['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= $project['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= $project['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= $project['priority'] === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                    <input type="number" name="budget" value="<?= e($project['budget'] ?? '') ?>" step="0.01" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Progress (%)</label>
                    <input type="number" name="progress" value="<?= e($project['progress']['percentage'] ?? 0) ?>" min="0" max="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="tinymce"><?= e($project['description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-gold-500"></i>Timeline
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="<?= e($project['start_date'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target End Date</label>
                    <input type="date" name="end_date" value="<?= e($project['end_date'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
        </div>
        
        <!-- Current Tasks Summary -->
        <?php if (!empty($tasks)): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-tasks mr-2 text-gold-500"></i>Tasks Summary
            </h2>
            
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span><?= count(array_filter($tasks, fn($t) => $t['status'] === 'completed')) ?> of <?= count($tasks) ?> tasks completed</span>
                <a href="<?= url('projects/view?id=' . $project['id']) ?>#tasks" class="text-gold-500 hover:text-gold-600">
                    Manage Tasks <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-2">
                <?php 
                $completed = count(array_filter($tasks, fn($t) => $t['status'] === 'completed'));
                $percentage = count($tasks) > 0 ? ($completed / count($tasks)) * 100 : 0;
                ?>
                <div class="bg-green-500 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <!-- Save Button (Part of Main Form) -->
            <div class="flex-1 text-right">
                <a href="<?= url('projects/view?id=' . $project['id']) ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition mr-3">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
    
    <!-- Delete Form (Separate) -->
    <div class="mt-4 border-t border-gray-200 pt-4">
        <form action="<?= url('projects/delete?id=' . $project['id']) ?>" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this project? All tasks and milestones will also be deleted.')">
            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
            <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition text-sm">
                <i class="fas fa-trash mr-2"></i>Delete Project
            </button>
        </form>
    </div>
</div>
