<!-- Flash Messages -->
<?php $flash = Session::getFlash(); ?>

<?php if (!empty($flash['success'])): ?>
<?php $successMsg = is_array($flash['success']) ? implode(', ', $flash['success']) : $flash['success']; ?>
<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-4 right-4 z-50 max-w-sm"
>
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium"><?= e($successMsg) ?></p>
        </div>
        <button @click="show = false" class="flex-shrink-0 text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($flash['error'])): ?>
<?php $errorMsg = is_array($flash['error']) ? implode(', ', $flash['error']) : $flash['error']; ?>
<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-4 right-4 z-50 max-w-sm"
>
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium"><?= e($errorMsg) ?></p>
        </div>
        <button @click="show = false" class="flex-shrink-0 text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($flash['warning'])): ?>
<?php $warningMsg = is_array($flash['warning']) ? implode(', ', $flash['warning']) : $flash['warning']; ?>
<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-init="setTimeout(() => show = false, 7000)"
    class="fixed top-4 right-4 z-50 max-w-sm"
>
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium"><?= e($warningMsg) ?></p>
        </div>
        <button @click="show = false" class="flex-shrink-0 text-yellow-500 hover:text-yellow-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($flash['info'])): ?>
<?php $infoMsg = is_array($flash['info']) ? implode(', ', $flash['info']) : $flash['info']; ?>
<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-4 right-4 z-50 max-w-sm"
>
    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium"><?= e($infoMsg) ?></p>
        </div>
        <button @click="show = false" class="flex-shrink-0 text-blue-500 hover:text-blue-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
<?php endif; ?>
