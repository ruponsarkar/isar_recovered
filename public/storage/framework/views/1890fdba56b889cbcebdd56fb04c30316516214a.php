<nav class="navbar navbar-expand-lg navbar-dark isar-nav sticky-top" aria-label="Journal navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo e(route('journal', $slug)); ?>"><?php echo e(($journal['abbr_title'] ?? '') ?: ($journal['j_name'] ?? 'Journal')); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#journalNav" aria-controls="journalNav" aria-expanded="false" aria-label="Toggle journal navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="journalNav">
            <ul class="navbar-nav ms-auto">
                <?php $__currentLoopData = collect($journalPages)->whereNull('parent_id')->where('show_in_nav', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($entry['type'] === 'group'): ?>
                        <?php $children = collect($journalPages)->where('parent_id', $entry['id'])->where('show_in_nav', true); ?>
                        <?php if($children->isNotEmpty()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?php echo e(($activePage['parent_id'] ?? null) == $entry['id'] ? 'active' : ''); ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo e($entry['title']); ?></a>
                                <ul class="dropdown-menu">
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><a class="dropdown-item <?php echo e(($activePage['id'] ?? null) == $child['id'] ? 'active' : ''); ?>" href="<?php echo e(\App\Services\JournalPages::href($child, $slug)); ?>" <?php if(($activePage['id'] ?? null) == $child['id']): ?> aria-current="page" <?php endif; ?>><?php echo e($child['title']); ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link <?php echo e(($activePage['id'] ?? null) == $entry['id'] ? 'active' : ''); ?>" href="<?php echo e(\App\Services\JournalPages::href($entry, $slug)); ?>" <?php if(($activePage['id'] ?? null) == $entry['id']): ?> aria-current="page" <?php endif; ?>><?php echo e($entry['title']); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/partials/journal-nav.blade.php ENDPATH**/ ?>