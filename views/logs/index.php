<?php
/**
 * TSILIZY CORE - Activity Logs View
 * Display system activity logs with filtering
 */
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
            <p class="text-gray-500 mt-1">Monitor system activity and user actions</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= url('logs/export', ['date_from' => $dateFrom ?? '', 'date_to' => $dateTo ?? '']) ?>" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Export CSV
            </a>
            <?php if (Auth::hasRole('super_admin')): ?>
            <button onclick="document.getElementById('clearLogsModal').classList.remove('hidden')" 
                    class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                <i class="fas fa-trash mr-2"></i>
                Clear Old Logs
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="<?= url('logs') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="filter" value="<?= e($filter ?? '') ?>" 
                       placeholder="Search actions, entities..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="<?= e($dateFrom ?? '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="<?= e($dateTo ?? '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gold-500 text-white rounded-lg hover:bg-gold-600 transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <?php if (empty($logs)): ?>
            <div class="p-12 text-center">
                <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Activity Logs Found</h3>
                <p class="text-gray-500">No logs match your current filters.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="font-medium text-gray-900"><?= formatDate($log['created_at'], 'd M Y') ?></div>
                                    <div class="text-xs text-gray-400"><?= formatDate($log['created_at'], 'H:i:s') ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gold-100 flex items-center justify-center text-gold-600 text-sm font-semibold mr-3">
                                            <?= strtoupper(substr($log['user_name'] ?? 'S', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?= e($log['user_name'] ?? 'System') ?></div>
                                            <div class="text-xs text-gray-400"><?= e($log['user_email'] ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $actionColors = [
                                        'login' => 'bg-green-100 text-green-800',
                                        'logout' => 'bg-gray-100 text-gray-800',
                                        'create' => 'bg-blue-100 text-blue-800',
                                        'update' => 'bg-amber-100 text-amber-800',
                                        'delete' => 'bg-red-100 text-red-800',
                                        'archive' => 'bg-purple-100 text-purple-800',
                                    ];
                                    $color = $actionColors[$log['action']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $color ?>">
                                        <?= ucfirst(str_replace('_', ' ', $log['action'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="text-gray-900"><?= ucfirst(str_replace('_', ' ', $log['entity_type'])) ?></span>
                                    <?php if ($log['entity_id']): ?>
                                        <span class="text-gray-400">#<?= $log['entity_id'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded"><?= e($log['ip_address']) ?></code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($log['details']): ?>
                                        <button onclick="showLogDetails(<?= $log['id'] ?>)" 
                                                class="text-gold-600 hover:text-gold-800 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-300">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
                <div class="px-6 py-4 border-t border-gray-100">
                    <?php partial('pagination', ['pagination' => $pagination, 'baseUrl' => 'logs']); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Clear Logs Modal -->
<?php if (Auth::hasRole('super_admin')): ?>
<div id="clearLogsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Clear Old Logs</h3>
                <p class="text-sm text-gray-500">This action cannot be undone</p>
            </div>
        </div>
        
        <form action="<?= url('logs/clear') ?>" method="POST">
            <?= Session::csrfField() ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Delete logs older than:</label>
                <select name="older_than" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="30">30 days</option>
                    <option value="60">60 days</option>
                    <option value="90" selected>90 days</option>
                    <option value="180">180 days</option>
                    <option value="365">1 year</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('clearLogsModal').classList.add('hidden')" 
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Delete Logs
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Log Details</h3>
            <button onclick="document.getElementById('logDetailsModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="logDetailsContent" class="bg-gray-50 rounded-lg p-4">
            <pre class="text-sm text-gray-700 whitespace-pre-wrap overflow-x-auto"></pre>
        </div>
    </div>
</div>

<script>
function showLogDetails(logId) {
    fetch('<?= url('logs/view') ?>?id=' + logId)
        .then(response => response.json())
        .then(data => {
            if (data.log && data.log.details) {
                let details = data.log.details;
                try {
                    details = JSON.stringify(JSON.parse(details), null, 2);
                } catch (e) {
                    // Not JSON, use as is
                }
                document.querySelector('#logDetailsContent pre').textContent = details;
                document.getElementById('logDetailsModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error('Error loading log details:', err);
        });
}
</script>
