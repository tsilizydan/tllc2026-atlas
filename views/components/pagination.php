<!-- Pagination Component -->
<?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
<nav class="flex items-center justify-between">
    <div class="text-sm text-gray-500">
        Showing <span class="font-medium"><?= $pagination['offset'] + 1 ?></span> 
        to <span class="font-medium"><?= min($pagination['offset'] + $pagination['per_page'], $pagination['total']) ?></span> 
        of <span class="font-medium"><?= $pagination['total'] ?></span> results
    </div>
    
    <div class="flex items-center space-x-1">
        <!-- Previous -->
        <?php if ($pagination['has_prev']): ?>
        <a href="?page=<?= $pagination['current_page'] - 1 ?>" 
           class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <?php else: ?>
        <span class="px-3 py-1 border border-gray-200 rounded text-gray-300 cursor-not-allowed">
            <i class="fas fa-chevron-left text-xs"></i>
        </span>
        <?php endif; ?>
        
        <!-- Page Numbers -->
        <?php
        $start = max(1, $pagination['current_page'] - 2);
        $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
        ?>
        
        <?php if ($start > 1): ?>
        <a href="?page=1" class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition">1</a>
        <?php if ($start > 2): ?>
        <span class="px-2 text-gray-400">...</span>
        <?php endif; ?>
        <?php endif; ?>
        
        <?php for ($i = $start; $i <= $end; $i++): ?>
        <?php if ($i == $pagination['current_page']): ?>
        <span class="px-3 py-1 bg-gold-500 text-charcoal rounded font-medium"><?= $i ?></span>
        <?php else: ?>
        <a href="?page=<?= $i ?>" class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition"><?= $i ?></a>
        <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($end < $pagination['total_pages']): ?>
        <?php if ($end < $pagination['total_pages'] - 1): ?>
        <span class="px-2 text-gray-400">...</span>
        <?php endif; ?>
        <a href="?page=<?= $pagination['total_pages'] ?>" class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition"><?= $pagination['total_pages'] ?></a>
        <?php endif; ?>
        
        <!-- Next -->
        <?php if ($pagination['has_next']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?>" 
           class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 transition">
            <i class="fas fa-chevron-right text-xs"></i>
        </a>
        <?php else: ?>
        <span class="px-3 py-1 border border-gray-200 rounded text-gray-300 cursor-not-allowed">
            <i class="fas fa-chevron-right text-xs"></i>
        </span>
        <?php endif; ?>
    </div>
</nav>
<?php endif; ?>
