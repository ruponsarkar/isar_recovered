@extends('layouts.app')
@php
    $description = \Illuminate\Support\Str::limit(trim(strip_tags(html_entity_decode($article->abstract ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'))), 160, '.');
    $authors = trim(strip_tags(html_entity_decode($article->aname ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    $doi = $article->doi ?? '';
    $scholar = $article->googleScholar ?? '';
    $validScholar = filter_var($scholar, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($scholar, PHP_URL_SCHEME) ?? ''), ['https', 'http']);
@endphp
@push('head')
<link rel="stylesheet" href="{{ asset('read-article.css') }}">
<link rel="canonical" href="{{ route('article.show', $article->slug) }}">
<meta name="description" content="{{ $description }}">
<meta name="author" content="{{ $authors }}">
<meta name="keywords" content="{{ strip_tags($article->keywords ?? '') }}">
<meta name="DC.Description" xml:lang="en" content="{{ $description }}">
<meta name="DC.Identifier.DOI" content="{{ $doi }}">
<meta name="DC.Identifier.URI" content="{{ route('article.show', $article->slug) }}">
<meta name="DC.Language" content="{{ $journal->language ?? 'en' }}">
<meta name="DC.Source" content="{{ $journal->j_name }}">
<meta name="DC.Source.ISSN" content="{{ $journal->issn ?? '' }}">
<meta name="DC.Date.created" content="{{ $article->published_date ?? '' }}">
<meta name="citation_title" content="{{ $article->name }}">
<meta name="citation_author" content="{{ $authors }}">
<meta name="citation_doi" content="{{ $doi }}">
<meta name="citation_publication_date" content="{{ $article->published_date ?? '' }}">
<meta name="citation_journal_title" content="{{ $journal->j_name }}">
<meta name="citation_issn" content="{{ $journal->issn ?? '' }}">
<meta name="citation_volume" content="{{ $volume->name ?? '' }}">
<meta name="citation_issue" content="{{ $issue->name ?? '' }}">
@if(!empty($article->file))<meta name="citation_pdf_url" content="{{ route('article.download', $article->id) }}">@endif
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $article->name }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ route('article.show', $article->slug) }}">
@if(!empty($journal->photo))<meta property="og:image" content="{{ asset_url('journal/'.$journal->photo) }}">@endif
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
            @if(!empty($journal->photo))<hr><img src="{{ asset_url('journal/'.$journal->photo) }}" class="read-article-cover" alt="{{ $journal->j_name }} cover">@endif
        </aside>
    </div>
</div>
@endsection
