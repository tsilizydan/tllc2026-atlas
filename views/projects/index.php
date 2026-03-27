<!-- Projects List View -->

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <p class="text-gray-500 mt-1">Manage your projects and deliverables</p>
    </div>
    <div class="mt-4 sm:mt-0 flex items-center space-x-3">
        <a href="<?= url('projects/print-list') ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            <i class="fas fa-print mr-2"></i>Print
        </a>
        <a href="<?= url('projects/create') ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>New Project
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Projects</p>
        <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Active</p>
        <p class="text-2xl font-bold text-blue-600"><?= $stats['active'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Completed</p>
        <p class="text-2xl font-bold text-green-600"><?= $stats['completed'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-sm text-gray-500">Total Value</p>
        <p class="text-2xl font-bold text-gold-500"><?= formatCurrency($stats['total_value'] ?? 0) ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('projects') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                name="search" 
                value="<?= e($search ?? '') ?>"
                placeholder="Search projects..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"
            >
        </div>
        <div>
            <select name="status" class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="planning" <?= ($status ?? '') === 'planning' ? 'selected' : '' ?>>Planning</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="on_hold" <?= ($status ?? '') === 'on_hold' ? 'selected' : '' ?>>On Hold</option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i>Search
        </button>
    </form>
</div>

<!-- Projects Grid -->
<?php if (!empty($projects)): ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($projects as $project): ?>
    <div class="bg-white rounded-xl border border-gray-200 hover:border-gold-300 hover:shadow-md transition overflow-hidden">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <a href="<?= url('projects/view?id=' . $project['id']) ?>" class="font-semibold text-gray-900 hover:text-gold-500">
                    <?= e($project['name']) ?>
                </a>
                <?= statusBadge($project['status']) ?>
            </div>
            
            <p class="text-sm text-gray-500 mb-3">
                <i class="fas fa-user mr-1"></i>
                <?= e($project['company_name'] ?? 'Internal') ?>
            </p>
            
            <?php if (!empty($project['description'])): ?>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2"><?= e(truncate($project['description'], 100)) ?></p>
            <?php endif; ?>
            
            <!-- Progress Bar -->
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">Progress</span>
                    <span class="font-medium text-gray-700"><?= $project['progress'] ?? 0 ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gold-500 h-2 rounded-full transition-all" style="width: <?= $project['progress'] ?? 0 ?>%"></div>
                </div>
            </div>
            
            <!-- Meta -->
            <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-100">
                <span>
                    <i class="fas fa-tasks mr-1"></i>
                    <?= $project['task_count'] ?? 0 ?> tasks
                </span>
                <?php if (!empty($project['end_date'])): ?>
                <span>
                    <i class="far fa-calendar mr-1"></i>
                    <?= formatDate($project['end_date']) ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Actions Footer -->
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end space-x-2">
            <a href="<?= url('projects/view?id=' . $project['id']) ?>" class="p-2 text-gray-400 hover:text-gray-600" title="View">
                <i class="fas fa-eye"></i>
            </a>
            <a href="<?= url('projects/edit?id=' . $project['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
<div class="mt-6">
    <?php include VIEWS_PATH . '/components/pagination.php'; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div class="empty-state bg-white rounded-xl border border-gray-200">
    <div class="empty-icon">
        <i class="fas fa-project-diagram text-3xl"></i>
    </div>
    <h3 class="empty-title">No projects found</h3>
    <p class="empty-desc">Create your first project to start tracking deliverables, tasks, and milestones.</p>
    <a href="<?= url('projects/create') ?>" class="inline-flex items-center px-5 py-2.5 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium shadow-sm">
        <i class="fas fa-plus mr-2"></i>Create Project
    </a>
</div>
<?php endif; ?>
