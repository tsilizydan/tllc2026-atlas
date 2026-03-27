<!-- Header -->
<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-gray-200 px-4 lg:px-6 shadow-sm">
    <div class="flex items-center justify-between h-16">
        <!-- Left: Toggle & Breadcrumb -->
        <div class="flex items-center space-x-4">
            <!-- Sidebar Toggle (hamburger) -->
            <button 
                @click="sidebarOpen = !sidebarOpen" 
                class="w-10 h-10 flex items-center justify-center rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 relative z-[50]"
                aria-label="Toggle sidebar menu"
                :aria-expanded="sidebarOpen"
            >
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-lg font-semibold text-gray-800">
                <?= e($pageTitle ?? 'Dashboard') ?>
            </h1>
        </div>
        
        <!-- Right: Actions & User -->
        <div class="flex items-center space-x-4">
            <!-- Quick Actions -->
            <div class="hidden md:flex items-center space-x-2">
                <a href="<?= url('clients/create') ?>" class="p-2 text-gray-500 hover:text-gold-500 hover:bg-gold-50 rounded-lg" title="New Client">
                    <i class="fas fa-user-plus"></i>
                </a>
                <a href="<?= url('invoices/create') ?>" class="p-2 text-gray-500 hover:text-gold-500 hover:bg-gold-50 rounded-lg" title="New Invoice">
                    <i class="fas fa-file-invoice"></i>
                </a>
                <a href="<?= url('projects/create') ?>" class="p-2 text-gray-500 hover:text-gold-500 hover:bg-gold-50 rounded-lg" title="New Project">
                    <i class="fas fa-plus-circle"></i>
                </a>
            </div>
            
            <!-- Divider -->
            <div class="hidden md:block w-px h-6 bg-gray-200"></div>
            
            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    @click.outside="open = false"
                    class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gold-500"
                >
                    <div class="w-8 h-8 bg-gold-500 rounded-full flex items-center justify-center">
                        <span class="text-charcoal font-semibold text-sm">
                            <?= strtoupper(substr(Auth::user()['first_name'] ?? 'U', 0, 1)) ?>
                        </span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-800">
                            <?= e(Auth::user()['first_name'] ?? 'User') ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            <?= e(Auth::user()['role_name'] ?? 'Staff') ?>
                        </p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                    x-cloak
                >
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-800">
                            <?= e((Auth::user()['first_name'] ?? '') . ' ' . (Auth::user()['last_name'] ?? '')) ?>
                        </p>
                        <p class="text-xs text-gray-500"><?= e(Auth::user()['email'] ?? '') ?></p>
                    </div>
                    
                    <a href="<?= url('profile') ?>" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user w-5 text-gray-400"></i>
                        <span>Profile</span>
                    </a>
                    
                    <?php if (Auth::hasPermission('company', 'edit')): ?>
                    <a href="<?= url('company') ?>" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog w-5 text-gray-400"></i>
                        <span>Settings</span>
                    </a>
                    <?php endif; ?>
                    
                    <div class="border-t border-gray-100 mt-1 pt-1">
                        <form action="<?= url('logout') ?>" method="POST" class="block">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
                            <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
