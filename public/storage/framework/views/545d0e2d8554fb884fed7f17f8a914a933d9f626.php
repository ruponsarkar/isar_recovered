<?php
    $routeName = request()->route()?->getName() ?? '';
    $journalData = isset($journal) ? (array) $journal : [];
    $pageTitle = $title ?? match ($routeName) {
        'home' => 'ISAR Publisher | Open Access Research Journals',
        'journals' => 'Research Journals | ISAR Publisher',
        'journal' => ($journalData['j_name'] ?? 'Journal').' | '.ucwords(str_replace('_', ' ', $view ?? 'current_issue')),
        'articles' => ($issue['name'] ?? 'Issue').' | Research Articles',
        'contact' => 'Contact ISAR Publisher',
        default => 'ISAR Publisher',
    };
    if (isset($activePage)) $pageTitle = $activePage['title'].' | '.($journalData['j_name'] ?? 'Journal');
    $metaDescription = $description ?? 'Explore peer-reviewed open access research journals, published articles, editorial boards, and author guidelines at ISAR Publisher.';
    if (in_array($routeName, ['journal', 'journal.page'], true)) $metaDescription = \Illuminate\Support\Str::limit(\App\Services\Seo::text($journalData['about'] ?? $journalData['description'] ?? '').' Explore '.($journalData['j_name'] ?? 'this journal').', current issues, archives and editorial board.', 160);
    $canonicalPath = request()->getPathInfo();
    if (in_array($routeName, ['journal', 'journal.page'], true)) {
        $canonicalPath = '/journal/'.rawurlencode($journalData['slug'] ?? $slug);
        if (in_array($view ?? '', ['about', 'archive', 'editorial_board'], true)) $canonicalPath .= '?view='.$view;
    }
    if (isset($activePage)) {
        $base = '/journal/'.rawurlencode($journalData['slug'] ?? $slug);
        $canonicalPath = $base.'/pages/'.rawurlencode($activePage['slug']);
        if ($activePage['type'] === 'system') {
            $canonicalPath = $base.($activePage['system_key'] === 'current_issue' ? '' : '?view='.$activePage['system_key']);
        }
        if ($activePage['slug'] === 'about') $canonicalPath = $base.'?view=about';
        if ($activePage['type'] === 'content') $metaDescription = \Illuminate\Support\Str::limit(\App\Services\Seo::text($activePage['content'] ?? ''), 160);
    }
    $canonical = \App\Services\Seo::url($canonicalPath);
    $noindex = str_starts_with($routeName, 'payment') || str_starts_with($routeName, 'pay.') || in_array($routeName, ['manuscript', 'join.author', 'join.reviewer', 'join.editor'], true) || in_array(request()->getPathInfo(), ['/joinReviewer', '/joinEditor'], true);
    $image = !empty($journalData['photo']) ? asset_url('journal/'.$journalData['photo']) : null;
    $siteSchema = ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'ISAR Publisher', 'url' => \App\Services\Seo::url('/')];
    if (in_array($routeName, ['journal', 'journal.page'], true)) $siteSchema = ['@context'=>'https://schema.org', '@type'=>'Periodical', 'name'=>$journalData['j_name'] ?? 'Journal', 'url'=>\App\Services\Seo::url('/journal/'.rawurlencode($journalData['slug'] ?? $slug)), 'publisher'=>['@type'=>'Organization','name'=>'ISAR Publisher']] + (!empty($journalData['issn']) ? ['issn'=>$journalData['issn']] : []);
?>
<title><?php echo e($pageTitle); ?></title>
<meta name="description" content="<?php echo e($metaDescription); ?>">
<meta name="robots" content="<?php echo e($noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large'); ?>">
<link rel="canonical" href="<?php echo e($canonical); ?>">
<meta property="og:site_name" content="ISAR Publisher">
<meta property="og:type" content="<?php echo e($routeName === 'article.show' ? 'article' : 'website'); ?>">
<meta property="og:title" content="<?php echo e($pageTitle); ?>">
<meta property="og:description" content="<?php echo e($metaDescription); ?>">
<meta property="og:url" content="<?php echo e($canonical); ?>">
<meta name="twitter:card" content="<?php echo e($image ? 'summary_large_image' : 'summary'); ?>">
<meta name="twitter:title" content="<?php echo e($pageTitle); ?>">
<meta name="twitter:description" content="<?php echo e($metaDescription); ?>">
<?php if($image): ?><meta property="og:image" content="<?php echo e($image); ?>"><meta name="twitter:image" content="<?php echo e($image); ?>"><?php endif; ?>
<script type="application/ld+json"><?php echo json_encode($siteSchema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES); ?></script>
<?php /**PATH /Users/ruponsarkar/Documents/projects/isar/isar/public/resources/views/partials/seo.blade.php ENDPATH**/ ?>