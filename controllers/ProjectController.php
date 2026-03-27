<?php
/**
 * TSILIZY CORE - Project Controller
 */

class ProjectController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    /**
     * List all projects
     */
    public function index(): void
    {
        Auth::requirePermission('projects', 'view');

        $status = input('status', '');
        $projects = Project::allWithClient();

        if ($status) {
            $projects = array_filter($projects, fn($p) => $p['status'] === $status);
        }

        view('projects/index', [
            'pageTitle' => 'Projects',
            'projects' => $projects,
            'stats' => Project::getStats(),
            'currentStatus' => $status
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        Auth::requirePermission('projects', 'create');

        view('projects/create', [
            'pageTitle' => 'Create Project',
            'clients' => Client::dropdown()
        ]);
    }

    /**
     * Store new project
     */
    public function store(): void
    {
        Auth::requirePermission('projects', 'create');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects');
        }

        $validator = Validator::make()
            ->required('name', 'Project Name')
            ->maxLength('name', 150);

        if ($validator->fails()) {
            storeOldInput();
            Session::flash('error', array_values($validator->errors())[0]);
            redirect('projects/create');
        }

        $data = [
            'name' => input('name'),
            'client_id' => input('client_id') ?: null,
            'description' => input('description'),
            'status' => input('status', 'planning'),
            'start_date' => input('start_date') ?: null,
            'end_date' => input('end_date') ?: null,
            'budget' => input('budget') ? (float) input('budget') : null,
            'created_by' => Auth::id()
        ];

        $id = Project::create($data);
        Auth::logActivity(Auth::id(), 'create', 'project', $id);

        clearOldInput();
        Session::flash('success', 'Project created successfully.');
        redirect('projects/view?id=' . $id);
    }

    /**
     * Show edit form
     */
    public function edit(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');
        $project = Project::findOrFail($id);

        view('projects/edit', [
            'pageTitle' => 'Edit Project',
            'project' => $project,
            'clients' => Client::dropdown()
        ]);
    }

    /**
     * Update project
     */
    public function update(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects');
        }

        $data = [
            'name' => input('name'),
            'client_id' => input('client_id') ?: null,
            'description' => input('description'),
            'status' => input('status'),
            'start_date' => input('start_date') ?: null,
            'end_date' => input('end_date') ?: null,
            'budget' => input('budget') ? (float) input('budget') : null
        ];

        Project::update($id, $data);
        Auth::logActivity(Auth::id(), 'update', 'project', $id);

        Session::flash('success', 'Project updated successfully.');
        redirect('projects/view?id=' . $id);
    }

    /**
     * View project
     */
    public function view(): void
    {
        Auth::requirePermission('projects', 'view');

        $id = (int) input('id');
        $project = Project::findWithDetails($id);

        if (!$project) {
            Router::notFound();
        }

        $project['progress'] = Project::getProgress($id);
        $project['milestone_progress'] = Milestone::getProjectProgress($id);

        view('projects/view', [
            'pageTitle' => $project['name'],
            'project' => $project
        ]);
    }

    /**
     * Archive project
     */
    public function archive(): void
    {
        Auth::requirePermission('projects', 'archive');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects');
        }

        Project::archive($id, Auth::id());
        Auth::logActivity(Auth::id(), 'archive', 'project', $id);

        Session::flash('success', 'Project archived successfully.');
        redirect('projects');
    }

    /**
     * Delete project
     */
    public function delete(): void
    {
        Auth::requirePermission('projects', 'delete');

        $id = (int) input('id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects');
        }

        Project::delete($id);
        Auth::logActivity(Auth::id(), 'delete', 'project', $id);

        Session::flash('success', 'Project deleted successfully.');
        redirect('projects');
    }

    // ========== TASK METHODS ==========

    /**
     * Store new task
     */
    public function storeTask(): void
    {
        Auth::requirePermission('projects', 'edit');

        $projectId = (int) input('project_id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects/view?id=' . $projectId);
        }

        $data = [
            'project_id' => $projectId,
            'title' => input('title'),
            'description' => input('description'),
            'status' => input('status', 'todo'),
            'priority' => input('priority', 'medium'),
            'assigned_to' => input('assigned_to') ?: null,
            'due_date' => input('due_date') ?: null,
            'created_by' => Auth::id()
        ];

        Task::create($data);
        Session::flash('success', 'Task added successfully.');
        redirect('projects/view?id=' . $projectId);
    }

    /**
     * Update task
     */
    public function updateTask(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');
        $projectId = (int) input('project_id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects/view?id=' . $projectId);
        }

        $data = [
            'title' => input('title'),
            'description' => input('description'),
            'status' => input('status'),
            'priority' => input('priority'),
            'assigned_to' => input('assigned_to') ?: null,
            'due_date' => input('due_date') ?: null
        ];

        if (input('status') === 'done') {
            $data['completed_at'] = date(DATETIME_FORMAT);
        }

        Task::update($id, $data);
        Session::flash('success', 'Task updated successfully.');
        redirect('projects/view?id=' . $projectId);
    }

    /**
     * Delete task
     */
    public function deleteTask(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');
        $projectId = (int) input('project_id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects/view?id=' . $projectId);
        }

        Task::delete($id);
        Session::flash('success', 'Task deleted successfully.');
        redirect('projects/view?id=' . $projectId);
    }

    /**
     * Toggle task status
     */
    public function toggleTask(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');
        $task = Task::find($id);

        if (!$task) {
            Session::flash('error', 'Task not found.');
            redirect('projects');
            return;
        }

        // Toggle between completed and todo
        $newStatus = $task['status'] === 'completed' ? 'todo' : 'completed';
        $data = ['status' => $newStatus];
        
        if ($newStatus === 'completed') {
            $data['completed_at'] = date(DATETIME_FORMAT);
        } else {
            $data['completed_at'] = null;
        }

        Task::update($id, $data);
        redirect('projects/view?id=' . $task['project_id']);
    }

    // ========== MILESTONE METHODS ==========

    /**
     * Store new milestone
     */
    public function storeMilestone(): void
    {
        Auth::requirePermission('projects', 'edit');

        $projectId = (int) input('project_id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects/view?id=' . $projectId);
        }

        $data = [
            'project_id' => $projectId,
            'title' => input('title'),
            'description' => input('description'),
            'due_date' => input('due_date') ?: null,
            'status' => 'pending'
        ];

        Milestone::create($data);
        Session::flash('success', 'Milestone added successfully.');
        redirect('projects/view?id=' . $projectId);
    }

    /**
     * Update milestone
     */
    public function updateMilestone(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');
        $projectId = (int) input('project_id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects/view?id=' . $projectId);
        }

        $data = [
            'title' => input('title'),
            'description' => input('description'),
            'due_date' => input('due_date') ?: null,
            'status' => input('status')
        ];

        if (input('status') === 'achieved') {
            $data['achieved_at'] = date(DATETIME_FORMAT);
        }

        Milestone::update($id, $data);
        Session::flash('success', 'Milestone updated successfully.');
        redirect('projects/view?id=' . $projectId);
    }

    /**
     * Delete milestone
     */
    public function deleteMilestone(): void
    {
        Auth::requirePermission('projects', 'edit');

        $id = (int) input('id');
        $projectId = (int) input('project_id');

        if (!isPost() || !Session::validateCsrfToken(input(CSRF_TOKEN_NAME, ''))) {
            redirect('projects/view?id=' . $projectId);
        }

        Milestone::delete($id);
        Session::flash('success', 'Milestone deleted successfully.');
        redirect('projects/view?id=' . $projectId);
    }

    // ========== PRINT METHODS ==========

    /**
     * Print project list
     */
    public function printList(): void
    {
        Auth::requirePermission('projects', 'print');

        $projects = Project::allWithClient();
        $company = CompanyProfile::get();

        printView('project_list_print', [
            'pageTitle' => 'Project List',
            'projects' => $projects ?? [],
            'company' => $company ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT)
        ]);
    }

    /**
     * Print project details
     */
    public function printDetails(): void
    {
        Auth::requirePermission('projects', 'print');

        $id = (int) input('id');
        $project = Project::findWithDetails($id);

        if (!$project) {
            Router::notFound();
        }

        $project['progress'] = Project::getProgress($id);
        $tasks = $project['tasks'] ?? [];
        $milestones = $project['milestones'] ?? [];
        $completedCount = count(array_filter($tasks, fn($t) => ($t['status'] ?? '') === 'completed'));
        $stats = [
            'total_tasks' => count($tasks),
            'completed_tasks' => $completedCount,
            'pending_tasks' => count($tasks) - $completedCount
        ];

        printView('project_report_print', [
            'pageTitle' => 'Project Report: ' . ($project['name'] ?? ''),
            'project' => $project,
            'company' => $company ?? [],
            'printDate' => date(DISPLAY_DATE_FORMAT),
            'stats' => $stats,
            'milestones' => $milestones,
            'tasks' => $tasks
        ]);
    }
}
