<?php if (! $__env->hasRenderedOnce('98659f1c-df43-45f1-99ae-8fbbbdc3837d')): $__env->markAsRenderedOnce('98659f1c-df43-45f1-99ae-8fbbbdc3837d'); ?>
    <link href="<?php echo e(asset('top-editors.css')); ?>" rel="stylesheet">
<?php endif; ?>
<div class="side-card">
    <div class="section-title">Applications</div>
    
    <a href="/JoinReviewer">Join as
        Reviewer</a><a href="/JoinEditor">Join as Editor</a>
</div>
<div class="side-card">
    <div class="section-title">Journals</div>
    <?php $__empty_1 = true; $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a
        href="<?php echo e(route('journal', $j['slug'] ?? ($j['j_id'] ?? 'journal'))); ?>"><?php echo e($j['j_name'] ?? 'ISAR Journal'); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><a
            href="/AllJournals">Browse ISAR Journals</a>
    <?php endif; ?>
</div>
<?php if(!empty($editors)): ?><?php ($editorSlides = array_chunk(array_values($editors), 2)); ?>
    <div class="side-card top-editors-card">
        <div class="section-title">Top Editors</div>
        <div id="topEditorsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <?php $__currentLoopData = $editorSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-item <?php echo e($loop->first ? 'active' : ''); ?>">
                        <div class="top-editors-grid">
                            <?php $__currentLoopData = $slide; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <figure class="top-editor"><img
                                        src="<?php echo e(asset_url('editors/' . ($editor['image'] ?? ''))); ?>"
                                        alt="<?php echo e($editor['name'] ?? 'ISAR editor'); ?>">
                                    <figcaption><?php echo e($editor['name'] ?? 'Editorial member'); ?></figcaption>
                                </figure>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div><?php endif; ?> <?php if(!empty($indexings)): ?>
        <div class="side-card">
            <div class="section-title">Indexing &amp; Abstracting</div>
            <div class="p-2 d-flex flex-wrap gap-2 justify-content-center">
                <?php $__currentLoopData = array_values($indexings); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indexing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($indexingImage = $indexing['img'] ?? ($indexing['image'] ?? ($indexing['photo'] ?? ($indexing['logo'] ?? null)))); ?><?php if($indexingImage): ?>
                        <a href="<?php echo e($indexing['link'] ?? '#'); ?>" target="_blank" rel="noopener"><img src="<?php echo e(asset_url('indexing/' . $indexingImage)); ?>"
                            alt="<?php echo e($indexing['name'] ?? 'Indexing service'); ?>"
                        style="max-width:72px;max-height:48px;object-fit:contain"></a><?php else: ?><span
                            class="badge text-bg-light"><?php echo e($indexing['name'] ?? 'Indexed'); ?></span>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
    
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/sidebar.blade.php ENDPATH**/ ?>