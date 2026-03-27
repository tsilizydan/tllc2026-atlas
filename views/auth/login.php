<h2 class="text-xl font-semibold text-white mb-6">Sign In</h2>

<form action="<?= url('login') ?>" method="POST" class="space-y-5">
    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= Session::getCsrfToken() ?>">
    
    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                <i class="fas fa-envelope"></i>
            </span>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?= e(old('email')) ?>"
                class="w-full pl-10 pr-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition"
                placeholder="you@example.com"
                required
                autofocus
            >
        </div>
    </div>
    
    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
        <div class="relative" x-data="{ show: false }">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                <i class="fas fa-lock"></i>
            </span>
            <input 
                :type="show ? 'text' : 'password'"
                id="password" 
                name="password" 
                class="w-full pl-10 pr-12 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition"
                placeholder="••••••••"
                required
            >
            <button 
                type="button"
                @click="show = !show"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300"
            >
                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
            </button>
        </div>
    </div>
    
    <!-- Remember Me -->
    <div class="flex items-center justify-between">
        <label class="flex items-center cursor-pointer">
            <input 
                type="checkbox" 
                name="remember" 
                class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-gold-500 focus:ring-gold-500 focus:ring-offset-gray-900"
            >
            <span class="ml-2 text-sm text-gray-400">Remember me</span>
        </label>
    </div>
    
    <!-- Submit Button -->
    <button 
        type="submit"
        class="w-full py-3 px-4 bg-gold-500 hover:bg-gold-600 text-charcoal font-semibold rounded-lg transition duration-200 flex items-center justify-center space-x-2"
    >
        <span>Sign In</span>
        <i class="fas fa-arrow-right"></i>
    </button>
</form>

<!-- Security Note -->
<div class="mt-6 text-center">
    <p class="text-xs text-gray-500">
        <i class="fas fa-shield-alt mr-1"></i>
        This is a secure system. Unauthorized access is prohibited.
    </p>
</div>
