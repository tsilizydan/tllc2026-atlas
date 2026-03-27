<!-- Users Management Index -->

<?php
$users = $users ?? [];
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-500 mt-1">Manage system users and access</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="<?= url('users/create') ?>" class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add User
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="<?= url('users') ?>" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>" placeholder="Search users..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
        </div>
        <div>
            <select name="role" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Roles</option>
                <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="manager" <?= ($_GET['role'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
                <option value="employee" <?= ($_GET['role'] ?? '') === 'employee' ? 'selected' : '' ?>>Employee</option>
                <option value="client" <?= ($_GET['role'] ?? '') === 'client' ? 'selected' : '' ?>>Client</option>
            </select>
        </div>
        <div>
            <select name="status" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold-500">
                <option value="">All Status</option>
                <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <?php if (!empty($users)): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= upload($user['avatar']) ?>" class="w-10 h-10 rounded-full object-cover mr-3">
                            <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center mr-3">
                                <span class="text-gold-600 font-medium text-sm">
                                    <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-medium text-gray-900"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></p>
                                <p class="text-sm text-gray-500">@<?= e($user['username'] ?? strtolower($user['first_name'])) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            <?php 
                            switch ($user['role_slug'] ?? $user['role'] ?? 'user') {
                                case 'admin': echo 'bg-purple-100 text-purple-700'; break;
                                case 'manager': echo 'bg-blue-100 text-blue-700'; break;
                                case 'employee': echo 'bg-green-100 text-green-700'; break;
                                default: echo 'bg-gray-100 text-gray-700';
                            }
                            ?>">
                            <?= ucfirst($user['role_name'] ?? $user['role_slug'] ?? 'User') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= e($user['email']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= $user['last_login'] ? formatDateTime($user['last_login']) : 'Never' ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= statusBadge($user['is_active'] ? 'active' : 'inactive') ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= url('users/edit?id=' . $user['id']) ?>" class="p-2 text-gray-400 hover:text-blue-600 transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($user['id'] !== Auth::id()): ?>
                            <form action="<?= url('users/toggle?id=' . $user['id']) ?>" method="POST" class="inline">
                                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                                <button type="submit" class="p-2 text-gray-400 hover:text-yellow-600 transition" title="<?= $user['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                    <i class="fas fa-<?= $user['is_active'] ? 'ban' : 'check-circle' ?>"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($pagination)): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php include VIEWS_PATH . '/components/pagination.php'; ?>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="text-center py-12">
        <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
        <p class="text-gray-500 mb-4">Get started by adding a new user.</p>
        <a href="<?= url('users/create') ?>" class="inline-flex items-center px-4 py-2 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
            <i class="fas fa-plus mr-2"></i>Add User
        </a>
    </div>
    <?php endif; ?>
</div>
