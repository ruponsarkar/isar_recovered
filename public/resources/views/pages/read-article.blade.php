@extends('layouts.app')
@php
    $description = \Illuminate\Support\Str::limit(trim(strip_tags(html_entity_decode($article->abstract ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 160, '.');
    $authors = implode(', ', array_column($citationAuthors, 'name'));
    $doi = \App\Services\Seo::doi($article->doi ?? '');
    $scholar = $article->googleScholar ?? '';
    $validScholar = filter_var($scholar, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($scholar, PHP_URL_SCHEME) ?? ''), ['https', 'http']);
@endphp
@push('head')
<link rel="stylesheet" href="{{ asset('read-article.css') }}">
<meta name="author" content="{{ $authors }}">
<meta name="keywords" content="{{ strip_tags($article->keywords ?? '') }}">
<meta name="DC.Description" xml:lang="en" content="{{ $description }}">
@if($doi)<meta name="DC.Identifier.DOI" content="{{ $doi }}">@endif
<meta name="DC.Identifier.URI" content="{{ \App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug)) }}">
<meta name="DC.Language" content="{{ strtolower($journal->language ?? '') === 'english' ? 'en' : ($journal->language ?? 'en') }}">
<meta name="DC.Source" content="{{ $journal->j_name }}">
<meta name="DC.Source.ISSN" content="{{ $journal->issn ?? '' }}">
@if(!empty($article->published_date))<meta name="DC.Date.issued" content="{{ $article->published_date }}">@endif
<meta name="citation_title" content="{{ $article->name }}">
@foreach($citationAuthors as $citationAuthor)
<meta name="citation_author" content="{{ $citationAuthor['name'] }}">
@if($citationAuthor['affiliation'])<meta name="citation_author_institution" content="{{ $citationAuthor['affiliation'] }}">@endif
<meta name="DC.Creator" content="{{ $citationAuthor['name'] }}">
@endforeach
<meta name="DC.Title" content="{{ $article->name }}">
<meta name="DC.Type" content="Text">
<meta name="DC.Publisher" content="ISAR Publisher">
<meta name="citation_publisher" content="ISAR Publisher">
<meta name="citation_abstract_html_url" content="{{ \App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug)) }}">
@if($doi)<meta name="citation_doi" content="{{ $doi }}">@endif
@if(!empty($article->published_date))<meta name="citation_publication_date" content="{{ str_replace('-', '/', $article->published_date) }}">@endif
<meta name="citation_journal_title" content="{{ $journal->j_name }}">
<meta name="citation_issn" content="{{ $journal->issn ?? '' }}">
<meta name="citation_volume" content="{{ preg_replace('/^volume\s*/i', '', $volume->name ?? '') }}">
<meta name="citation_issue" content="{{ preg_replace('/^issue\s*/i', '', $issue->name ?? '') }}">
@php
    $pages = preg_split('/\s*[-–—]\s*/u', trim($article->page ?? ''));
    $articleUrl = \App\Services\Seo::url('/viewArticle/'.rawurlencode($article->slug));
    $pdfUrl = \App\Services\Seo::url(\App\Services\Seo::pdfPath($article));
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
    if (!empty($journal->photo)) $schema['image'] = backend_asset_url('journal/'.$journal->photo);
@endphp
@if(!empty($pages[0]))<meta name="citation_firstpage" content="{{ $pages[0] }}">@endif
@if(!empty($pages[1]))<meta name="citation_lastpage" content="{{ $pages[1] }}">@endif
@if(!empty($article->file))<meta name="citation_pdf_url" content="{{ $pdfUrl }}">@endif
<script type="application/ld+json">{!! json_encode($schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
@section('content')
<div class="container read-article-layout">
    <div class="row g-0">
        <article class="col-md-8 read-article-main">
            <h1 class="read-article-title">{{ $article->name }}</h1>
            <hr>
            <div class="read-article-field">Author: <strong>{{ author_text($article->aname ?? '') }}</strong>
                @if(!empty($article->designation))<div class="read-article-affiliations">{{ author_text($article->designation) }}</div>@endif
            </div>
            <div class="read-article-field">Published Date: <strong>{{ $article->published_date ?? '—' }}</strong></div>
            @if($doi)
                <div class="read-article-field">DOI: <a href="https://doi.org/{{ $doi }}" target="_blank" rel="noopener">{{ $doi }}</a></div>
            @endif
            @foreach(['received'=>'Received', 'revised'=>'Revised', 'accepted'=>'Accepted'] as $field=>$label)
                @if(!empty($article->$field))
                    <div class="read-article-field">{{ $label }}: <strong>{{ $article->$field }}</strong></div>
                @endif
            @endforeach
            @if($validScholar)<div class="read-article-field">GoogleScholar: <strong><a href="{{ $scholar }}" target="_blank" rel="noopener">Click here</a></strong></div>@endif
            <div class="read-article-field">Keywords: <i>{{ $article->keywords ?? '—' }}</i></div>
            <strong>Abstract:</strong>
            <div class="read-article-abstract">{!! $article->abstract ?? '' !!}</div>
        </article>
        <aside class="col-md-4 read-article-aside" aria-label="Journal and download details">
            <div class="text-center py-2">
                @if(!empty($article->file))<a href="{{ route('article.download', $article->id) }}" class="btn btn-success"><i class="fa-solid fa-download"></i> &nbsp; Download</a>@else<span class="text-muted">PDF unavailable</span>@endif
                <span class="btn btn-secondary" aria-label="{{ $article->count ?? 0 }} downloads"><i class="fa-solid fa-eye"></i> &nbsp; {{ $article->count ?? 0 }}</span>
            </div>
            <hr>
            <dl>
                <dt>Journal: </dt><dd>{{ $journal->j_name }}</dd>
                <dt>ISSN(Online): </dt><dd>{{ $journal->issn ?? '—' }}</dd>
                <dt>Publisher: </dt><dd>ISAR Publishers</dd>
                <dt>Frequency: </dt><dd>{{ $journal->frequency ?? '—' }}</dd>
                <dt>Language: </dt><dd>{{ $journal->language ?? '—' }}</dd>
            </dl>
            @if(!empty($journal->photo))<hr><img src="{{ backend_asset_url('journal/'.$journal->photo) }}" class="read-article-cover" alt="{{ $journal->j_name }} cover">@endif
        </aside>
    </div>
</div>
@endsection
