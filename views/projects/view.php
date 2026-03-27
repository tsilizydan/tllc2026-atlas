<!-- View Project -->

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <a href="<?= url('projects') ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
            <h1 class="text-2xl font-bold text-gray-900"><?= e($project['name']) ?></h1>
            <div class="flex items-center space-x-3 mt-2">
                <?= statusBadge($project['status']) ?>
                <?php if (!empty($project['company_name'])): ?>
                <span class="text-gray-500">|</span>
                <a href="<?= url('clients/view?id=' . $project['client_id']) ?>" class="text-sm text-blue-500 hover:underline">
                    <?= e($project['company_name']) ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <a href="<?= url('projects/print?id=' . $project['id']) ?>" target="_blank" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-print mr-2"></i>Print
            </a>
            <a href="<?= url('projects/edit?id=' . $project['id']) ?>" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>
    
    <!-- Progress & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Overall Progress</h3>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gold-500 h-4 rounded-full transition-all" style="width: <?= $project['progress']['percentage'] ?? 0 ?>%"></div>
                    </div>
                </div>
                <span class="text-2xl font-bold text-gold-500"><?= $project['progress']['percentage'] ?? 0 ?>%</span>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Budget</p>
            <p class="text-2xl font-bold text-gray-900"><?= formatCurrency($project['budget'] ?? 0) ?></p>
        </div>
        
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Deadline</p>
            <p class="text-2xl font-bold text-gray-900"><?= !empty($project['end_date']) ? formatDate($project['end_date']) : 'N/A' ?></p>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Details & Tasks -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <?php if (!empty($project['description'])): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Description</h2>
                <p class="text-gray-600"><?= nl2br(e($project['description'])) ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Tasks -->
            <div class="bg-white rounded-xl border border-gray-200" x-data="{ showAddTask: false }">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Tasks</h2>
                    <button @click="showAddTask = !showAddTask" class="text-sm text-gold-500 hover:text-gold-600">
                        <i class="fas" :class="showAddTask ? 'fa-times' : 'fa-plus'"></i>
                        <span x-text="showAddTask ? 'Cancel' : 'Add Task'"></span>
                    </button>
                </div>
                
                <!-- Add Task Form -->
                <div x-show="showAddTask" x-transition class="p-5 bg-gray-50 border-b border-gray-200">
                    <form action="<?= url('projects/tasks/store') ?>" method="POST" class="space-y-4">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="title" placeholder="Task title" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <input type="date" name="due_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i>Add Task
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Tasks List -->
                <?php if (!empty($project['tasks'])): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($project['tasks'] as $task): ?>
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <form action="<?= url('projects/toggle-task?id=' . $task['id']) ?>" method="POST" class="inline">
                                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                                <button type="submit" class="w-5 h-5 border-2 rounded flex items-center justify-center transition
                                    <?= $task['status'] === 'completed' ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-gold-500' ?>">
                                    <?php if ($task['status'] === 'completed'): ?>
                                    <i class="fas fa-check text-xs"></i>
                                    <?php endif; ?>
                                </button>
                            </form>
                            <div>
                                <p class="font-medium text-gray-900 <?= $task['status'] === 'completed' ? 'line-through text-gray-400' : '' ?>">
                                    <?= e($task['title']) ?>
                                </p>
                                <?php if (!empty($task['due_date'])): ?>
                                <p class="text-xs text-gray-500">
                                    <i class="far fa-calendar mr-1"></i><?= formatDate($task['due_date']) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?= statusBadge($task['status']) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-tasks text-3xl mb-2 opacity-50"></i>
                    <p>No tasks yet. Add your first task above.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Right Column: Milestones & Info -->
        <div class="space-y-6">
            <!-- Project Info -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Start Date</p>
                        <p class="text-sm text-gray-900"><?= !empty($project['start_date']) ? formatDate($project['start_date']) : 'Not set' ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">End Date</p>
                        <p class="text-sm text-gray-900"><?= !empty($project['end_date']) ? formatDate($project['end_date']) : 'Not set' ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Priority</p>
                        <p class="text-sm text-gray-900"><?= ucfirst($project['priority'] ?? 'medium') ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Created</p>
                        <p class="text-sm text-gray-900"><?= formatDateTime($project['created_at']) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Milestones -->
            <div class="bg-white rounded-xl border border-gray-200" x-data="{ showAddMilestone: false }">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Milestones</h2>
                    <button @click="showAddMilestone = !showAddMilestone" class="text-sm text-gold-500 hover:text-gold-600">
                         <i class="fas" :class="showAddMilestone ? 'fa-times' : 'fa-plus'"></i>
                         <span x-text="showAddMilestone ? 'Cancel' : 'Add'"></span>
                    </button>
                </div>
                
                <!-- Add Milestone Form -->
                <div x-show="showAddMilestone" x-transition class="p-5 bg-gray-50 border-b border-gray-200">
                    <form action="<?= url('projects/milestones/store') ?>" method="POST" class="space-y-4">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                        <div class="grid grid-cols-1 gap-4">
                            <input type="text" name="title" placeholder="Milestone title" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <textarea name="description" placeholder="Description (optional)" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500"></textarea>
                            <input type="date" name="due_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i>Save Milestone
                            </button>
                        </div>
                    </form>
                </div>
                
                <?php if (!empty($project['milestones'])): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($project['milestones'] as $milestone): ?>
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <p class="font-medium text-gray-900"><?= e($milestone['title']) ?></p>
                            <?= statusBadge($milestone['status']) ?>
                        </div>
                        <?php if (!empty($milestone['due_date'])): ?>
                        <p class="text-xs text-gray-500">
                            <i class="far fa-calendar mr-1"></i><?= formatDate($milestone['due_date']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-flag text-3xl mb-2 opacity-50"></i>
                    <p>No milestones yet</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
