<?php
    $description = \Illuminate\Support\Str::limit(trim(strip_tags(html_entity_decode($article->abstract ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 160, '.');
    $authors = implode(', ', array_column($citationAuthors, 'name'));
    $doi = $article->doi ?? '';
    $scholar = $article->googleScholar ?? '';
    $validScholar = filter_var($scholar, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($scholar, PHP_URL_SCHEME) ?? ''), ['https', 'http']);
?>
<?php $__env->startPush('head'); ?>
<link rel="stylesheet" href="<?php echo e(asset('read-article.css')); ?>">
<meta name="author" content="<?php echo e($authors); ?>">
<meta name="keywords" content="<?php echo e(strip_tags($article->keywords ?? '')); ?>">
<meta name="DC.Description" xml:lang="en" content="<?php echo e($description); ?>">
<meta name="DC.Identifier.DOI" content="<?php echo e($doi); ?>">
<meta name="DC.Identifier.URI" content="<?php echo e(\App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug))); ?>">
<meta name="DC.Language" content="<?php echo e($journal->language ?? 'en'); ?>">
<meta name="DC.Source" content="<?php echo e($journal->j_name); ?>">
<meta name="DC.Source.ISSN" content="<?php echo e($journal->issn ?? ''); ?>">
<meta name="DC.Date.created" content="<?php echo e($article->published_date ?? ''); ?>">
<meta name="citation_title" content="<?php echo e($article->name); ?>">
<?php $__currentLoopData = $citationAuthors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $citationAuthor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<meta name="citation_author" content="<?php echo e($citationAuthor['name']); ?>">
<?php if($citationAuthor['affiliation']): ?><meta name="citation_author_institution" content="<?php echo e($citationAuthor['affiliation']); ?>"><?php endif; ?>
<meta name="DC.Creator" content="<?php echo e($citationAuthor['name']); ?>">
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<meta name="DC.Title" content="<?php echo e($article->name); ?>">
<meta name="DC.Type" content="Text">
<meta name="DC.Publisher" content="ISAR Publisher">
<meta name="citation_publisher" content="ISAR Publisher">
<meta name="citation_abstract_html_url" content="<?php echo e(\App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug))); ?>">
<meta name="citation_doi" content="<?php echo e($doi); ?>">
<meta name="citation_publication_date" content="<?php echo e(str_replace('-', '/', $article->published_date ?? '')); ?>">
<meta name="citation_journal_title" content="<?php echo e($journal->j_name); ?>">
<meta name="citation_issn" content="<?php echo e($journal->issn ?? ''); ?>">
<meta name="citation_volume" content="<?php echo e(preg_replace('/^volume\s*/i', '', $volume->name ?? '')); ?>">
<meta name="citation_issue" content="<?php echo e(preg_replace('/^issue\s*/i', '', $issue->name ?? '')); ?>">
<?php
    $pages = preg_split('/\s*[-–—]\s*/u', trim($article->page ?? ''));
    $articleUrl = \App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug));
    $pdfUrl = \App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug).'/fulltext.pdf');
    $schema = ['@context'=>'https://schema.org', '@type'=>'ScholarlyArticle', '@id'=>$articleUrl, 'url'=>$articleUrl,
        'mainEntityOfPage'=>$articleUrl, 'headline'=>$article->name, 'name'=>$article->name,
        'abstract'=>\App\Services\Seo::text($article->abstract), 'keywords'=>\App\Services\Seo::text($article->keywords),
        'author'=>array_map(fn($a)=>['@type'=>'Person', 'name'=>$a['name']] + ($a['affiliation'] ? ['affiliation'=>['@type'=>'Organization','name'=>$a['affiliation']]] : []), $citationAuthors),
        'publisher'=>['@type'=>'Organization', 'name'=>'ISAR Publisher'],
        'isPartOf'=>['@type'=>'PublicationIssue','issueNumber'=>preg_replace('/^issue\s*/i','',$issue->name),
            'isPartOf'=>['@type'=>'PublicationVolume','volumeNumber'=>preg_replace('/^volume\s*/i','',$volume->name),
                'isPartOf'=>['@type'=>'Periodical','name'=>$journal->j_name,'issn'=>$journal->issn ?? '']]]];
    if ($article->published_date) $schema['datePublished'] = $article->published_date;
    if ($doi) $schema['identifier'] = ['@type'=>'PropertyValue','propertyID'=>'DOI','value'=>$doi];
    if (!empty($article->file)) $schema['encoding'] = ['@type'=>'MediaObject','encodingFormat'=>'application/pdf','contentUrl'=>$pdfUrl];
    if (!empty($journal->photo)) $schema['image'] = asset_url('journal/'.$journal->photo);
?>
<?php if(!empty($pages[0])): ?><meta name="citation_firstpage" content="<?php echo e($pages[0]); ?>"><?php endif; ?>
<?php if(!empty($pages[1])): ?><meta name="citation_lastpage" content="<?php echo e($pages[1]); ?>"><?php endif; ?>
<?php if(!empty($article->file)): ?><meta name="citation_pdf_url" content="<?php echo e($pdfUrl); ?>"><?php endif; ?>
<script type="application/ld+json"><?php echo json_encode($schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES); ?></script>
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