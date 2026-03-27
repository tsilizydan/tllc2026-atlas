<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Login') ?> - TSILIZY CORE</title>
    
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
    
    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-charcoal min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8 animate__animated animate__fadeInDown">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gold-500 rounded-2xl mb-4 shadow-lg shadow-gold-500/30">
                <span class="text-charcoal text-3xl font-bold">T</span>
            </div>
            <h1 class="text-2xl font-bold text-white">TSILIZY CORE</h1>
            <p class="text-warm-gray mt-1">Corporate Intranet Portal</p>
        </div>
        
        <!-- Flash Messages -->
        <?php $flash = Session::getFlash(); ?>
        <?php if (!empty($flash['success'])): ?>
            <?php $successMsg = is_array($flash['success']) ? implode(', ', $flash['success']) : $flash['success']; ?>
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6 animate__animated animate__fadeIn">
                <i class="fas fa-check-circle mr-2"></i>
                <?= e($successMsg) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($flash['error'])): ?>
            <?php $errorMsg = is_array($flash['error']) ? implode(', ', $flash['error']) : $flash['error']; ?>
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6 animate__animated animate__shakeX">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= e($errorMsg) ?>
            </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl border border-gray-800 p-8 animate__animated animate__fadeInUp">
            <?= $content ?? '' ?>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-warm-gray text-sm mt-8">
            &copy; <?= date('Y') ?> TSILIZY LLC. All rights reserved.
        </p>
    </div>
</body>
</html>
