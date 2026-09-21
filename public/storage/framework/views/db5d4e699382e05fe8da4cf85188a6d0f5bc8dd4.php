
<?php if(count($factors)): ?>
    <div class="side-card">
        <div class="section-title">Impact Factor</div>
        <?php $__currentLoopData = $factors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="impact" href="<?php echo e($factor['link'] ?? '#'); ?>"
                target="_blank"><strong><?php echo e($factor['impact'] ?? ''); ?></strong></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?> <?php if(count($indexings)): ?>
        <div class="side-card">
            <div class="section-title">Indexing</div>
            <div class="logo-grid">
                <?php $__currentLoopData = $indexings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($i['link'] ?? '#'); ?>" target="_blank" rel="noopener"><img
                            src="<?php echo e(asset_url('indexing/' . ($i['img'] ?? ''))); ?>"
                            onerror="this.src='<?php echo e(asset_url('indexings/' . ($i['img'] ?? ''))); ?>'"
                            alt="Indexing service"></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div><?php endif; ?> <?php if(count($certificates)): ?>
            <div class="side-card">
                <div class="section-title">Certificate</div>
                <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img class="certificate" src="<?php echo e(asset_url('certificate/' . ($c['img'] ?? ''))); ?>"
                        alt="Journal certificate">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/journal-sidebar.blade.php ENDPATH**/ ?>