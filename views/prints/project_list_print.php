<?php $projects = $projects ?? []; ?>
<table>
    <thead>
        <tr>
            <th>Project</th>
            <th>Client</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Budget</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $project): ?>
        <tr>
            <td>
                <strong><?= e($project['name'] ?? '') ?></strong>
            </td>
            <td><?= e($project['client_name'] ?? '-') ?></td>
            <td>
                <span class="badge badge-<?= ($project['status'] ?? '') === 'completed' ? 'success' : (($project['status'] ?? '') === 'planning' ? 'info' : 'warning') ?>">
                    <?= ucfirst($project['status'] ?? '') ?>
                </span>
            </td>
            <td><?= formatDate($project['start_date'] ?? '') ?></td>
            <td><?= formatDate($project['end_date'] ?? '') ?></td>
            <td><?= formatCurrency($project['budget'] ?? 0) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="6" style="text-align: center; color: #999;">No projects found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row">
        <span>Total Projects</span>
        <span><?= count($projects) ?></span>
    </div>
</div>
