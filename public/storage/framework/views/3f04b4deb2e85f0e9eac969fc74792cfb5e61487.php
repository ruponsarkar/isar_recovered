 <?php $__env->startSection('content'); ?>
    <div class="container mt-3">
        <article class="paper-card">
            <div class="section-title">All Journals</div>
            <div class="paper-body">
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $journals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-6 mb-3">
                            <div class="border h-100 p-2">
                                <div class="row h-100">
                                    <div class="col-5 d-flex align-items-center"><img class="journal-cover"
                                            src="<?php echo e(backend_asset_url('journal/' . ($j['photo'] ?? 'journals/j1.jpg'))); ?>"
                                            alt="Journal cover"></div>
                                    <div class="col-7">
                                        <div class="journal-name small"><?php echo e($j['j_name'] ?? 'ISAR Journal'); ?></div>
                                        <small><b>Abbr. Title:</b> <?php echo e($j['abbr_title'] ?? '—'); ?><br><b>ISSN (Online):</b>
                                            <?php echo e($j['issn'] ?? '—'); ?><br><b>Publisher:</b> ISAR Publisher<br><b>Language:</b>
                                            English</small>
                                        <div class="text-end mt-2"><a class="btn btn-info btn-sm"
                                                href="<?php echo e(route('journal', $j['slug'] ?? ($j['j_id'] ?? 'journal'))); ?>">Read
                                                More</a></div>
                                    </div>
                                </div>
                            </div>
                    </div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="empty">No journals are currently available. Configure database credentials
                            or <code>PUBLIC_API_BASE_URL</code> to load the existing catalogue.</div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/journals.blade.php ENDPATH**/ ?>