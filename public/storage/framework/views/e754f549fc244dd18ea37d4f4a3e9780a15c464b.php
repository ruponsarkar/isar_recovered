<?php $__env->startSection('content'); ?>
    <?php
        $clean = fn($v) => $v && !in_array(strtolower(trim((string) $v)), ['undefined', 'null']);
        $sortedIssues = collect($issues)->sortBy(fn($i) => (int) ($i['issue_id'] ?? ($i['id'] ?? 0)));
        $latestIssue = $sortedIssues->last();
        $latestVolume = $latestIssue ? collect($volumes)->first(fn($v) => (string) ($v['id'] ?? '') === (string) ($latestIssue['v_id'] ?? '')) ?? collect($volumes)->firstWhere('name', $latestIssue['volume'] ?? null) : null;
    ?>
    <div class="container-fluid mt-3">
        <div class="row g-3">
            
            <main class="col-lg-9">
                <article class="paper-card journal-identity">
                    <div class="section-title text-center"><?php echo e($journal['j_name'] ?? 'ISAR Journal'); ?></div>
                    <div class="paper-body">
                        <dl class="journal-details">
                            <div><dt>Abbr. Title:</dt><dd><?php echo e($journal['abbr_title'] ?? '—'); ?></dd></div>
                            <?php if(($journal['j_name'] ?? '') === 'ISAR Journal of Economics and Business Management'): ?>
                                <div><dt>Subject:</dt><dd>Business management/ public relation</dd></div>
                            <?php endif; ?>
                            <div><dt>ISSN(Online):</dt><dd><?php echo e($journal['issn'] ?? '—'); ?></dd></div>
                            <div><dt>Publisher:</dt><dd>ISAR Publisher</dd></div>
                            <div><dt>Chief Editor:</dt><dd><?php echo e($chief['name'] ?? '—'); ?></dd></div>
                            <div><dt>Country of origin:</dt><dd><?php echo e($journal['country_of_origin'] ?? '—'); ?></dd></div>
                            <div><dt>Language:</dt><dd><?php echo e($journal['language'] ?? 'English'); ?></dd></div>
                            <div><dt>Frequency:</dt><dd><?php echo e($journal['frequency'] ?? '—'); ?></dd></div>
                            <div><dt>Format:</dt><dd>Online</dd></div>
                            <div><dt>Journal starting year:</dt><dd><?php echo e($journal['starting_year'] ?? '—'); ?></dd></div>
                        </dl>
                    </div>
                </article>
                <?php if(isset($journalPages)): ?>
                    <?php echo $__env->make('partials.journal-tabs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php else: ?>
                    <nav class="journal-tabs" aria-label="Journal sections">
                        <?php $__currentLoopData = ['about' => 'About', 'current_issue' => 'Current Issue', 'archive' => 'Archive', 'editorial_board' => 'Editorial Panel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a class="<?php echo e($view === $key ? 'active' : ''); ?>"
                                href="<?php echo e(route('journal', $slug)); ?>?view=<?php echo e($key); ?>"><?php echo e($label); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </nav>
                <?php endif; ?>
                <section class="paper-card tab-content-card">
                    <div class="paper-body">
                        <?php if($view === 'empty'): ?>
                            <div class="empty">No pages are published for this journal yet.</div>
                        <?php elseif($view === 'content'): ?>
                            <h2 class="content-heading"><?php echo e($activePage['title']); ?></h2>
                            <div class="rich-copy policy-copy"><?php echo \App\Support\JournalPageHtml::clean($activePage['content'] ?? ''); ?></div>
                        <?php elseif($view === 'about'): ?>
                            <h2 class="content-heading">Aim And Scope</h2>
                            <div class="rich-copy"><?php echo $journal['aim_and_scope'] ?? '<p>Aim and scope information is currently unavailable.</p>'; ?></div>
                        <?php elseif($view === 'current_issue'): ?>
                            <?php if($latestIssue): ?>
                                <a class="btn btn-outline-primary btn-sm"
                                    href="<?php echo e(route('articles', $latestIssue['issue_id'] ?? $latestIssue['id'])); ?>"><?php echo e($latestVolume['name'] ?? ($latestIssue['volume'] ?? 'Volume')); ?>

                                - <?php echo e($latestIssue['issues'] ?? ($latestIssue['name'] ?? 'Issue')); ?></a><?php else: ?><div
                                    class="empty">No current issue has been published for this journal yet.</div>
                            <?php endif; ?>
                        <?php elseif($view === 'archive'): ?>
                            <?php $__empty_1 = true; $__currentLoopData = collect($volumes)->sortByDesc(fn($volume) => (int) ($volume['year'] ?? 0)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $volume): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $volumeIssues=collect($issues)->filter(fn($i)=>(string)($i['v_id']??'')===(string)($volume['id']??'')||($volume['name']??null)===($i['volume']??null)); ?><section class="archive-volume">
                                    <h2><?php echo e($volume['year'] ?? ''); ?></h2>
                                    <?php $__empty_2 = true; $__currentLoopData = $volumeIssues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <a class="btn btn-outline-primary btn-sm"
                                            href="<?php echo e(route('articles', $issue['issue_id'] ?? $issue['id'])); ?>"><?php echo e($volume['name'] ?? ($issue['volume'] ?? 'Volume')); ?>,
                                        <?php echo e($issue['issues'] ?? ($issue['name'] ?? 'Issue')); ?></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?><div
                                            class="text-muted small px-2">No issues in this volume.</div>
                                    <?php endif; ?>
                                </section>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="empty">No archive issues are available yet.</div>
                            <?php endif; ?>
                        <?php elseif($view === 'editorial_board'): ?>
                            <?php $__currentLoopData = ['chief' => 'Chief Editor', 'ass' => 'Editorial Board member']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $heading): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <section class="editor-group">
                                    <h2><?php echo e($heading); ?></h2>
                                    <?php $__empty_1 = true; $__currentLoopData = collect($editors)->where('type',$type); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <article class="editor-card">
                                            <div class="row">
                                                <div class="col-md-3"><img
                                                        src="<?php echo e(asset_url('editors/' . ($editor['image'] ?? ''))); ?>"
                                                        alt="<?php echo e($editor['name'] ?? 'Editor'); ?>"></div>
                                                <div class="col-md-9"><strong><?php echo e($editor['name'] ?? ''); ?></strong>
                                                    <div><?php echo e($type === 'chief' ? 'Editor-in-Chief' : 'Editor'); ?></div>
                                                    <p><?php echo e($editor['details'] ?? ''); ?></p>
                                                    <?php if($clean($editor['profile'] ?? null)): ?>
                                                        <div>Profile Link: <a href="<?php echo e($editor['profile']); ?>"
                                                                target="_blank" rel="noopener"><?php echo e($editor['profile']); ?></a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if($clean($editor['biography'] ?? null)): ?>
                                                <h3>Biography</h3>
                                                <p><?php echo e($editor['biography']); ?></p>
                                                <?php endif; ?> <?php if($clean($editor['interest'] ?? null)): ?>
                                                    <h3>Research Interest</h3>
                                                    <p><?php echo e($editor['interest']); ?></p>
                                                <?php endif; ?>
                                        </article>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="empty">No <?php echo e(strtolower($heading)); ?> records are available.
                                        </div>
                                    <?php endif; ?>
                                </section>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </section>
                <?php echo $__env->make('pages.article-list', ['heading' => 'Latest Articles', 'articles' => $articles], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </main>
            <aside class="col-lg-3">
                <div class="paper-card journal-cover-card">
                    <div class="paper-body">
                        <img class="journal-cover" src="<?php echo e(asset_url('journal/' . ($journal['photo'] ?? ''))); ?>"
                            alt="<?php echo e($journal['j_name'] ?? 'Journal'); ?> cover">
                    </div>
                </div>
                <?php echo $__env->make('pages.journal-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->make('pages.sidebar', [
                'journals' => $journals,
                'editors' => null,
                'indexings' => null,
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </aside>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/journal.blade.php ENDPATH**/ ?>