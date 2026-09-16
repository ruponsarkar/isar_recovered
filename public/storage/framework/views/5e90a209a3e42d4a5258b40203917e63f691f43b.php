<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('site.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('journal.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('head'); ?>
    <link href="<?php echo e(asset('theme.css')); ?>" rel="stylesheet">
</head>

<body class="public-site <?php echo $__env->yieldContent('body-class'); ?>"><?php echo $__env->make('partials.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><a class="manu-btn" href="<?php echo e(route('manuscript')); ?>"><i
            class="fa-solid fa-paper-plane"></i> Submit Manuscript</a>
    <main>
        <?php if(session('success')): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?><button
                        class="btn-close" data-bs-dismiss="alert"></button></div>
            </div>
        <?php endif; ?> <?php echo $__env->yieldContent('content'); ?>
    </main><?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/layouts/app.blade.php ENDPATH**/ ?>