<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> - TSILIZY CORE</title>
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#fdf9e7',
                            100: '#faf2cf',
                            200: '#f5e59f',
                            300: '#f0d86f',
                            400: '#ebcb3f',
                            500: '#C9A227',
                            600: '#a78a1f',
                            700: '#856e18',
                            800: '#635310',
                            900: '#413708'
                        },
                        charcoal: '#0F0F0F',
                        'warm-gray': '#8E8E8E',
                        'soft-gray': '#E6E6E6'
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/q96zzqz4inb66gof20x44rx2hi6vdo7x980dqw1vc0ymu3io/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .sidebar-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scrollbar::-webkit-scrollbar-thumb {
            background: #C9A227;
            border-radius: 2px;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Form & Table UX improvements */
        .form-input, input[type="text"], input[type="email"], input[type="url"], input[type="tel"], 
        input[type="number"], input[type="date"], select, textarea {
            min-height: 2.5rem;
        }
        .form-input:focus, input:focus, select:focus, textarea:focus {
            outline: none;
        }
        table.data-table th, table.data-table td,
        .overflow-x-auto table th, .overflow-x-auto table td {
            padding: 0.875rem 1.25rem;
            vertical-align: middle;
        }
        .overflow-x-auto table tbody tr {
            transition: background-color 0.15s;
        }
        .overflow-x-auto table tbody tr:hover {
            background-color: #f9fafb;
        }
        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
            background: linear-gradient(180deg, #fafafa 0%, #f5f5f5 100%);
            border-radius: 0.75rem;
            border: 1px dashed #e5e7eb;
        }
        .empty-state .empty-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border-radius: 50%;
            color: #9ca3af;
        }
        .empty-state .empty-title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #111827;
            margin-bottom: 0.5rem;
        }
        .empty-state .empty-desc {
            color: #6b7280;
            margin-bottom: 1.25rem;
            max-width: 24rem;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024, userDropdown: false }"
      @keydown.escape.window="sidebarOpen = false">
    <div class="flex min-h-screen">
        <!-- Mobile Sidebar Overlay (click-outside to close) -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[35] lg:hidden" 
             x-cloak
             aria-hidden="true">
        </div>

        <!-- Sidebar -->
        <?php include VIEWS_PATH . '/layouts/partials/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen transition-all duration-200" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
            <!-- Header -->
            <?php include VIEWS_PATH . '/layouts/partials/header.php'; ?>
            
            <!-- Flash Messages -->
            <?php include VIEWS_PATH . '/layouts/partials/flash.php'; ?>
            
            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6">
                <?= $content ?? '' ?>
            </main>
            
            <!-- Footer -->
            <?php include VIEWS_PATH . '/layouts/partials/footer.php'; ?>
        </div>
    </div>
    
    <!-- Global Scripts -->
    <script>
        // Initialize TinyMCE for textareas with 'editor' class
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.editor').length > 0) {
                tinymce.init({
                    selector: '.editor',
                    height: 300,
                    menubar: false,
                    plugins: 'lists link',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link',
                    skin: 'oxide-dark',
                    content_css: 'dark'
                });
            }
        });
    </script>
</body>
</html>
