<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<url><loc><?php echo e($entry['loc']); ?></loc><?php if(isset($entry['lastmod'])): ?><lastmod><?php echo e($entry['lastmod']); ?></lastmod><?php endif; ?></url>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</urlset>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/seo/sitemap.blade.php ENDPATH**/ ?>