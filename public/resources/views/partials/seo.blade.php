@php
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
    $metaDescription = $description ?? 'Explore peer-reviewed open access research journals, published articles, editorial boards, and author guidelines at ISAR Publisher.';
    if ($routeName === 'journal') $metaDescription = \Illuminate\Support\Str::limit(\App\Services\Seo::text($journalData['about'] ?? $journalData['description'] ?? '').' Explore '.($journalData['j_name'] ?? 'this journal').', current issues, archives and editorial board.', 160);
    $canonicalPath = request()->getPathInfo();
    if ($routeName === 'journal') {
        $canonicalPath = '/journal/'.rawurlencode($journalData['slug'] ?? $slug);
        if (in_array($view ?? '', ['about', 'archive', 'editorial_board'], true)) $canonicalPath .= '?view='.$view;
    }
    $canonical = \App\Services\Seo::url($canonicalPath);
    $noindex = str_starts_with($routeName, 'payment') || str_starts_with($routeName, 'pay.') || in_array($routeName, ['manuscript', 'join.author', 'join.reviewer', 'join.editor'], true) || in_array(request()->getPathInfo(), ['/joinReviewer', '/joinEditor'], true);
    $image = !empty($journalData['photo']) ? asset_url('journal/'.$journalData['photo']) : null;
    $siteSchema = ['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'ISAR Publisher', 'url' => \App\Services\Seo::url('/')];
    if ($routeName === 'journal') $siteSchema = ['@context'=>'https://schema.org', '@type'=>'Periodical', 'name'=>$journalData['j_name'] ?? 'Journal', 'url'=>\App\Services\Seo::url('/journal/'.rawurlencode($journalData['slug'] ?? $slug)), 'publisher'=>['@type'=>'Organization','name'=>'ISAR Publisher']] + (!empty($journalData['issn']) ? ['issn'=>$journalData['issn']] : []);
@endphp
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="robots" content="{{ $noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large' }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:site_name" content="ISAR Publisher">
<meta property="og:type" content="{{ $routeName === 'article.show' ? 'article' : 'website' }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:url" content="{{ $canonical }}">
<meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
@if($image)<meta property="og:image" content="{{ $image }}"><meta name="twitter:image" content="{{ $image }}">@endif
<script type="application/ld+json">{!! json_encode($siteSchema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>
