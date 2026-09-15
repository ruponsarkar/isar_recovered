<?php
    $description = \Illuminate\Support\Str::limit(trim(strip_tags(html_entity_decode($article->abstract ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 160, '.');
    $authors = trim(strip_tags(html_entity_decode($article->aname ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    $doi = $article->doi ?? '';
    $scholar = $article->googleScholar ?? '';
    $validScholar = filter_var($scholar, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($scholar, PHP_URL_SCHEME) ?? ''), ['https', 'http']);
?>
<?php $__env->startPush('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('read-article.css')); ?>">
<link rel="canonical" href="<?php echo e(route('article.show', $article->slug)); ?>">
<meta name="description" content="<?php echo e($description); ?>">
<meta name="author" content="<?php echo e($authors); ?>">
<meta name="keywords" content="<?php echo e(strip_tags($article->keywords ?? '')); ?>">
<meta name="DC.Description" xml:lang="en" content="<?php echo e($description); ?>">
<meta name="DC.Identifier.DOI" content="<?php echo e($doi); ?>">
<meta name="DC.Identifier.URI" content="<?php echo e(route('article.show', $article->slug)); ?>">
<meta name="DC.Language" content="<?php echo e($journal->language ?? 'en'); ?>">
<meta name="DC.Source" content="<?php echo e($journal->j_name); ?>">
<meta name="DC.Source.ISSN" content="<?php echo e($journal->issn ?? ''); ?>">
<meta name="DC.Date.created" content="<?php echo e($article->published_date ?? ''); ?>">
<meta name="citation_title" content="<?php echo e($article->name); ?>">
<meta name="citation_author" content="<?php echo e($authors); ?>">
<meta name="citation_doi" content="<?php echo e($doi); ?>">
<meta name="citation_publication_date" content="<?php echo e($article->published_date ?? ''); ?>">
<meta name="citation_journal_title" content="<?php echo e($journal->j_name); ?>">
<meta name="citation_issn" content="<?php echo e($journal->issn ?? ''); ?>">
<meta name="citation_volume" content="<?php echo e($volume->name ?? ''); ?>">
<meta name="citation_issue" content="<?php echo e($issue->name ?? ''); ?>">
<?php if(!empty($article->file)): ?><meta name="citation_pdf_url" content="<?php echo e(route('article.download', $article->id)); ?>"><?php endif; ?>
<meta property="og:type" content="article">
<meta property="og:title" content="<?php echo e($article->name); ?>">
<meta property="og:description" content="<?php echo e($description); ?>">
<meta property="og:url" content="<?php echo e(route('article.show', $article->slug)); ?>">
<?php if(!empty($journal->photo)): ?><meta property="og:image" content="<?php echo e(asset_url('journal/'.$journal->photo)); ?>"><?php endif; ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="container read-article-layout">
    <div class="row g-0">
        <article class="col-md-8 read-article-main">
            <h1 class="read-article-title"><?php echo e($article->name); ?></h1>
            <hr>
            <div class="read-article-field">Author: <strong><?php echo e(author_text($article->aname ?? '')); ?></strong>
                <?php if(!empty($article->designation)): ?><div class="read-article-affiliations"><?php echo e(author_text($article->designation)); ?></div><?php endif; ?>
            </div>
            <div class="read-article-field">Published Date: <strong><?php echo e($article->published_date ?? '—'); ?></strong></div>
            <?php $__currentLoopData = ['received'=>'Received', 'revised'=>'Revised', 'accepted'=>'Accepted']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($article->$field)): ?>
                    <div class="read-article-field"><?php echo e($label); ?>: <strong><?php echo e($article->$field); ?></strong></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($validScholar): ?><div class="read-article-field">GoogleScholar: <strong><a href="<?php echo e($scholar); ?>" target="_blank" rel="noopener">Click here</a></strong></div><?php endif; ?>
            <div class="read-article-field">Keywords: <i><?php echo e($article->keywords ?? '—'); ?></i></div>
            <strong>Abstract:</strong>
            <div class="read-article-abstract"><?php echo $article->abstract ?? ''; ?></div>
        </article>
        <aside class="col-md-4 read-article-aside" aria-label="Journal and download details">
            <div class="text-center py-2">
                <?php if(!empty($article->file)): ?><a href="<?php echo e(route('article.download', $article->id)); ?>" class="btn btn-success"><i class="fa-solid fa-download"></i> &nbsp; Download</a><?php else: ?><span class="text-muted">PDF unavailable</span><?php endif; ?>
                <span class="btn btn-secondary" aria-label="<?php echo e($article->count ?? 0); ?> downloads"><i class="fa-solid fa-eye"></i> &nbsp; <?php echo e($article->count ?? 0); ?></span>
            </div>
            <hr>
            <dl>
                <dt>Journal: </dt><dd><?php echo e($journal->j_name); ?></dd>
                <dt>ISSN(Online): </dt><dd><?php echo e($journal->issn ?? '—'); ?></dd>
                <dt>Publisher: </dt><dd>ISAR Publishers</dd>
                <dt>Frequency: </dt><dd><?php echo e($journal->frequency ?? '—'); ?></dd>
                <dt>Language: </dt><dd><?php echo e($journal->language ?? '—'); ?></dd>
            </dl>
            <?php if(!empty($journal->photo)): ?><hr><img src="<?php echo e(asset_url('journal/'.$journal->photo)); ?>" class="read-article-cover" alt="<?php echo e($journal->j_name); ?> cover"><?php endif; ?>
        </aside>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/pages/read-article.blade.php ENDPATH**/ ?>