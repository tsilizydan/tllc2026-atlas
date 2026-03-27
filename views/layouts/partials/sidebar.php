<!-- Sidebar Navigation -->
<aside 
    class="fixed inset-y-0 left-0 z-[40] w-64 bg-charcoal border-r border-gray-800 transform transition-transform duration-200 ease-in-out overflow-y-auto sidebar-scrollbar shadow-xl lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-20'"
    x-cloak
    role="navigation"
    aria-label="Main navigation"
>
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 border-b border-gray-800 relative">
        <a href="<?= url('dashboard') ?>" class="flex items-center space-x-2">
            <?php $company = CompanyProfile::get() ?? []; ?>
            <?php if (!empty($company['logo_path'])): ?>
                <img src="<?= upload($company['logo_path'] ?? '', 'logo') ?>" alt="Logo" class="w-10 h-10 object-contain rounded-lg bg-white/10 p-1">
                <span class="text-white font-bold text-lg" x-show="sidebarOpen" x-transition><?= e($company['company_name'] ?? 'TSILIZY') ?></span>
            <?php else: ?>
                <div class="w-10 h-10 bg-gold-500 rounded-lg flex items-center justify-center">
                    <span class="text-charcoal text-xl font-bold"><?= substr($company['company_name'] ?? 'T', 0, 1) ?></span>
                </div>
                <span class="text-white font-bold text-lg" x-show="sidebarOpen" x-transition><?= e($company['company_name'] ?? 'TSILIZY') ?></span>
            <?php endif; ?>
        </a>
        
        <!-- Mobile Close Button -->
        <button @click="sidebarOpen = false" 
                class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition lg:hidden"
                aria-label="Close menu">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>
    
    <!-- Navigation -->
    <nav class="p-4 space-y-1">
        <!-- Dashboard -->
        <a href="<?= url('dashboard') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('dashboard') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-home w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Dashboard</span>
        </a>
        
        <!-- Clients -->
        <a href="<?= url('clients') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('clients') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-users w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Clients</span>
        </a>
        
        <!-- Projects -->
        <a href="<?= url('projects') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('projects') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-project-diagram w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Projects</span>
        </a>
        
        <!-- Invoices -->
        <a href="<?= url('invoices') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('invoices') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-file-invoice-dollar w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Invoices</span>
        </a>
        
        <!-- HR Section -->
        <?php if (Auth::hasPermission('hr', 'view')): ?>
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider" x-show="sidebarOpen">
                Human Resources
            </p>
        </div>
        
        <a href="<?= url('hr') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('hr') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-users-cog w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>HR Dashboard</span>
        </a>
        
        <a href="<?= url('hr/employees') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('hr/employees') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-id-card w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Employees</span>
        </a>
        
        <a href="<?= url('hr/paychecks') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('hr/paychecks') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-money-check-alt w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Paychecks</span>
        </a>
        <?php endif; ?>
        
        <!-- Business Section -->
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider" x-show="sidebarOpen">
                Business
            </p>
        </div>
        
        <a href="<?= url('contracts') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('contracts') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-file-contract w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Contracts</span>
        </a>
        
        <a href="<?= url('partners') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('partners') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-handshake w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Partners</span>
        </a>
        
        <?php if (Auth::hasPermission('assets', 'view')): ?>
        <a href="<?= url('assets') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('assets') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-box-open w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Assets</span>
        </a>
        <?php endif; ?>
        
        <!-- Finance Section -->
        <?php if (Auth::hasPermission('finance', 'view')): ?>
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider" x-show="sidebarOpen">
                Finance
            </p>
        </div>
        
        <a href="<?= url('finance') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('finance') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-chart-line w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Overview</span>
        </a>
        
        <a href="<?= url('finance/income') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('finance/income') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-arrow-up w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Income</span>
        </a>
        
        <a href="<?= url('finance/expenses') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('finance/expenses') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-arrow-down w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Expenses</span>
        </a>
        
        <a href="<?= url('finance/reports') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('finance/reports') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-file-alt w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Reports</span>
        </a>
        <?php endif; ?>
        
        <!-- Settings Section -->
        <?php if (Auth::hasPermission('company', 'view')): ?>
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider" x-show="sidebarOpen">
                Settings
            </p>
        </div>
        
        <a href="<?= url('company') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('company') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-building w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Company</span>
        </a>
        
        <a href="<?= url('company/services') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('company/services') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-concierge-bell w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Services</span>
        </a>
        
        <a href="<?= url('company/branding') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('company/branding') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-palette w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Branding</span>
        </a>
        
        <?php if (Auth::hasAnyRole(['admin', 'super_admin'])): ?>
        <a href="<?= url('users') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('users') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-user-shield w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Users</span>
        </a>
        <?php endif; ?>
        
        <?php if (Auth::hasPermission('logs', 'view')): ?>
        <a href="<?= url('logs') ?>" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-colors <?= isCurrentRoute('logs') ? 'bg-gold-500/10 text-gold-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-clipboard-list w-5 text-center"></i>
            <span x-show="sidebarOpen" x-transition>Activity Logs</span>
        </a>
        <?php endif; ?>
        <?php endif; ?>
    </nav>
</aside>
