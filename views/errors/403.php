<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | TSILIZY CORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { 500: '#C9A227' },
                        charcoal: '#0F0F0F'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-charcoal min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-32 h-32 bg-red-500/10 rounded-full mb-6">
                <i class="fas fa-lock text-6xl text-red-500"></i>
            </div>
        </div>
        
        <!-- Message -->
        <h1 class="text-3xl font-bold text-white mb-4">Access Denied</h1>
        <p class="text-gray-400 mb-8 max-w-md mx-auto">
            You don't have permission to access this page. 
            Please contact your administrator if you believe this is an error.
        </p>
        
        <!-- Actions -->
        <div class="flex items-center justify-center space-x-4">
            <a href="javascript:history.back()" class="px-6 py-3 border border-gray-700 text-gray-300 rounded-lg hover:bg-gray-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>Go Back
            </a>
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>dashboard" class="px-6 py-3 bg-gold-500 text-charcoal rounded-lg hover:bg-gold-600 transition font-medium">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
        </div>
        
        <!-- Logo -->
        <div class="mt-12 opacity-50">
            <div class="inline-flex items-center space-x-2">
                <div class="w-8 h-8 bg-gold-500 rounded-lg flex items-center justify-center">
                    <span class="text-charcoal font-bold">T</span>
                </div>
                <span class="text-gray-500 font-medium">TSILIZY CORE</span>
            </div>
        </div>
    </div>
</body>
</html>
