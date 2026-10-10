<?php $tabs = collect($journalPages)->where('show_in_tabs', true)->where('type', '!=', 'group'); ?>
<?php if($tabs->isNotEmpty()): ?>
<nav class="journal-tabs" aria-label="Journal sections">
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="<?php echo e(($activePage['id'] ?? null) == $entry['id'] ? 'active' : ''); ?>" href="<?php echo e(\App\Services\JournalPages::href($entry, $slug)); ?>" <?php if(($activePage['id'] ?? null) == $entry['id']): ?> aria-current="page" <?php endif; ?>><?php echo e($entry['title']); ?></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php endif; ?>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/partials/journal-tabs.blade.php ENDPATH**/ ?>