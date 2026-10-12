<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <article class="paper-card">
        <div class="policy-hero"><h1>Contact Us</h1></div>
        <div class="paper-body policy-copy">
            <p><b><?php echo e(data_get($journal ?? null, 'j_name') ?: 'International Scientific and Academic Research Publisher'); ?></b></p>
            <?php echo $__env->make('partials.contact-details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </article>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/contact.blade.php ENDPATH**/ ?>