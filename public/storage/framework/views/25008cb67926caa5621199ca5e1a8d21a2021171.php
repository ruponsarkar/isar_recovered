 <?php ($labels = ['manuscript' => 'Submit Manuscript', 'author' => 'Join as Author', 'reviewer' => 'Join as Reviewer', 'editor' => 'Join as Editor']); ?> <?php $__env->startSection('content'); ?><div class="container my-3">
        <article class="paper-card">
            <div class="section-title"><?php echo e($labels[$type]); ?></div>
            <div class="paper-body">
                <p><strong>You may also contact us at <a
                            href="mailto:isarpublishers@gmail.com">isarpublishers@gmail.com</a>.</strong></p>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="post" enctype="multipart/form-data"
                    action="<?php echo e($type === 'manuscript' ? route('manuscript.submit') : route('join.' . $type . '.submit')); ?>">
                    <?php echo csrf_field(); ?> <?php if($type === 'manuscript'): ?>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Process</label><select name="mode"
                                    class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option>First Track Process</option>
                                    <option>Normal Process</option>
                                </select></div>
                            <div class="col-md-6"><label class="form-label">Article Type</label><select name="article_type"
                                    class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option>Research article</option>
                                    <option>Review article</option>
                                    <option>Short Communication</option>
                                    <option>Case Report</option>
                                    <option>Letter to editor</option>
                                </select></div>
                            <div class="col-md-6"><label class="form-label">Journal</label><input name="journal"
                                    value="<?php echo e(old('journal')); ?>" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Manuscript Title</label><input name="title"
                                    value="<?php echo e(old('title')); ?>" class="form-control" required></div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label
                            class="form-label"><?php echo e($type === 'manuscript' ? 'Corresponding Author' : 'Full Name'); ?></label><input
                            name="name" value="<?php echo e(old('name')); ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email"
                            value="<?php echo e(old('email')); ?>" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Country</label><input name="country"
                            value="<?php echo e(old('country')); ?>" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Affiliation</label><input name="affiliation"
                            value="<?php echo e(old('affiliation')); ?>" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Phone</label><input name="phone"
                            value="<?php echo e(old('phone')); ?>" class="form-control" required></div>
                    <?php if($type !== 'manuscript'): ?>
                        <div class="col-md-6"><label class="form-label">Area of Expertise</label><input name="expertise"
                                value="<?php echo e(old('expertise')); ?>" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Publications</label><textarea name="publication"
                                class="form-control"><?php echo e(old('publication')); ?></textarea></div>
                        <div class="col-md-6"><label class="form-label">Profile Photo <small>(JPG, JPEG,
                                    PNG)</small></label><input type="file" accept=".jpg,.jpeg,.png" name="photo"
                                class="form-control" required></div>
                    <?php endif; ?>
                    <div class="col-12">
                        <label class="form-label"><?php echo e($type === 'manuscript' ? 'Article File' : 'CV / Supporting File'); ?>

                            <small>(PDF or DOCX)</small></label><input type="file" accept=".pdf,.docx" name="file"
                            class="form-control" required>
                    </div>
                    <div class="col-12 text-center"><button class="btn btn-isar px-4" type="submit">Submit
                            <?php echo e($type === 'manuscript' ? 'Manuscript' : 'Application'); ?> <i
                                class="fa-solid fa-paper-plane"></i></button></div>
                    <?php if($type === 'manuscript'): ?>
            </div>
            <?php endif; ?>
            </form>
    </div>
    </article>
</div><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/form.blade.php ENDPATH**/ ?>