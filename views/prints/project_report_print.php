<?php
$project = $project ?? [];
$company = $company ?? CompanyProfile::get() ?? [];
$stats = $stats ?? ['total_tasks' => 0, 'completed_tasks' => 0, 'pending_tasks' => 0];
$milestones = $milestones ?? $project['milestones'] ?? [];
$tasks = $tasks ?? $project['tasks'] ?? [];
?>
<style>
.project-report .status-row { display: flex; justify-content: space-between; margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 5px; flex-wrap: wrap; gap: 15px; }
.project-report .status-item { text-align: center; }
.project-report .status-label { font-size: 9pt; color: #666; text-transform: uppercase; }
.project-report .status-value { font-size: 14pt; font-weight: bold; }
.project-report .progress-bar { height: 20px; background: #e5e7eb; border-radius: 10px; overflow: hidden; margin: 10px 0 5px; }
.project-report .progress-fill { height: 100%; background: #C9A227; }
.project-report .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px; margin-bottom: 20px; }
.project-report .stat-box { background: #f9f9f9; padding: 15px; border-radius: 5px; text-align: center; }
.project-report .stat-value { font-size: 18pt; font-weight: bold; color: #333; }
.project-report .stat-label { font-size: 9pt; color: #666; text-transform: uppercase; }
.project-report .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.project-report .data-table th, .project-report .data-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
.project-report .data-table th { background: #f9f9f9; font-size: 9pt; color: #666; text-transform: uppercase; }
.project-report .section { margin-bottom: 25px; }
.project-report .section h2 { font-size: 12pt; padding-bottom: 8px; border-bottom: 1px solid #ddd; margin-bottom: 15px; }
</style>
<div class="project-report">
    <div class="project-title" style="margin-bottom: 20px;">
        <h1 style="font-size: 22pt; margin-bottom: 5px;"><?= e($project['name'] ?? '') ?></h1>
        <?php if (!empty($project['client_name'])): ?>
        <div style="font-size: 12pt; color: #666;">Client: <?= e($project['client_name']) ?></div>
        <?php endif; ?>
    </div>
    <div class="status-row">
        <div class="status-item">
            <div class="status-label">Status</div>
            <div class="status-value"><?= ucwords(str_replace('_', ' ', $project['status'] ?? 'Active')) ?></div>
        </div>
        <div class="status-item">
            <div class="status-label">Priority</div>
            <div class="status-value"><?= ucfirst($project['priority'] ?? 'Medium') ?></div>
        </div>
        <div class="status-item">
            <div class="status-label">Start Date</div>
            <div class="status-value"><?= formatDate($project['start_date'] ?? '') ?></div>
        </div>
        <div class="status-item">
            <div class="status-label">End Date</div>
            <div class="status-value"><?= formatDate($project['end_date'] ?? '') ?></div>
        </div>
    </div>
    <div class="progress-section" style="margin-bottom: 25px;">
        <h3 style="font-size: 10pt; color: #666; margin-bottom: 10px;">Overall Progress</h3>
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= min(100, max(0, (int)($project['progress'] ?? 0))) ?>%"></div>
        </div>
        <div style="text-align: right; font-size: 10pt; color: #666;"><?= $project['progress'] ?? 0 ?>% Complete</div>
    </div>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-value"><?= $stats['total_tasks'] ?? 0 ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" style="color: #10b981;"><?= $stats['completed_tasks'] ?? 0 ?></div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" style="color: #f59e0b;"><?= $stats['pending_tasks'] ?? 0 ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-box">
            <div class="stat-value" style="color: #C9A227;"><?= formatCurrency($project['budget'] ?? 0) ?></div>
            <div class="stat-label">Budget</div>
        </div>
    </div>
    <?php if (!empty($milestones)): ?>
    <div class="section">
        <h2>Milestones</h2>
        <table class="data-table">
            <thead><tr><th>Milestone</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($milestones as $m): ?>
                <tr>
                    <td><?= e($m['title'] ?? '') ?></td>
                    <td><?= formatDate($m['due_date'] ?? '') ?></td>
                    <td><span class="badge badge-<?= ($m['status'] ?? '') === 'completed' ? 'success' : 'warning' ?>"><?= ucfirst($m['status'] ?? '') ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php if (!empty($tasks)): ?>
    <div class="section">
        <h2>Tasks</h2>
        <table class="data-table">
            <thead><tr><th>Task</th><th>Assigned To</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($tasks as $t): ?>
                <tr>
                    <td><?= e($t['title'] ?? '') ?></td>
                    <td><?= e($t['assigned_to'] ?? 'Unassigned') ?></td>
                    <td><?= formatDate($t['due_date'] ?? '') ?></td>
                    <td><span class="badge badge-<?= ($t['status'] ?? '') === 'completed' ? 'success' : 'warning' ?>"><?= ucwords(str_replace('_', ' ', $t['status'] ?? '')) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <?php if (!empty($project['description'])): ?>
    <div class="section">
        <h2>Project Description</h2>
        <div style="font-size: 10pt; line-height: 1.8;"><?= nl2br(e($project['description'])) ?></div>
    </div>
    <?php endif; ?>
</div>
