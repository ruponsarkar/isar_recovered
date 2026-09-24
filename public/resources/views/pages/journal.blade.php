@extends('layouts.app')
@section('content')
    @php
        $clean = fn($v) => $v && !in_array(strtolower(trim((string) $v)), ['undefined', 'null']);
        $sortedIssues = collect($issues)->sortBy(fn($i) => (int) ($i['issue_id'] ?? ($i['id'] ?? 0)));
        $latestIssue = $sortedIssues->last();
        $latestVolume = $latestIssue ? collect($volumes)->first(fn($v) => (string) ($v['id'] ?? '') === (string) ($latestIssue['v_id'] ?? '')) ?? collect($volumes)->firstWhere('name', $latestIssue['volume'] ?? null) : null;
    @endphp
    <div class="container-fluid mt-3">
        <div class="row g-3">
            {{-- <aside class="col-lg-3 d-none d-lg-block">@include('pages.sidebar', [
                'journals' => $journals,
                'editors' => $topEditors,
                'indexings' => $globalIndexings,
            ])</aside> --}}
            <main class="col-lg-9">
                <article class="paper-card journal-identity">
                    <div class="section-title text-center">{{ $journal['j_name'] ?? 'ISAR Journal' }}</div>
                    <div class="paper-body">
                        <dl class="journal-details">
                            <div><dt>Abbr. Title:</dt><dd>{{ $journal['abbr_title'] ?? '—' }}</dd></div>
                            @if (($journal['j_name'] ?? '') === 'ISAR Journal of Economics and Business Management')
                                <div><dt>Subject:</dt><dd>Business management/ public relation</dd></div>
                            @endif
                            <div><dt>ISSN(Online):</dt><dd>{{ $journal['issn'] ?? '—' }}</dd></div>
                            <div><dt>Publisher:</dt><dd>ISAR Publisher</dd></div>
                            <div><dt>Chief Editor:</dt><dd>{{ $chief['name'] ?? '—' }}</dd></div>
                            <div><dt>Country of origin:</dt><dd>{{ $journal['country_of_origin'] ?? '—' }}</dd></div>
                            <div><dt>Language:</dt><dd>{{ $journal['language'] ?? 'English' }}</dd></div>
                            <div><dt>Frequency:</dt><dd>{{ $journal['frequency'] ?? '—' }}</dd></div>
                            <div><dt>Format:</dt><dd>Online</dd></div>
                            <div><dt>Journal starting year:</dt><dd>{{ $journal['starting_year'] ?? '—' }}</dd></div>
                        </dl>
                    </div>
                </article>
                @if (isset($journalPages))
                    @include('partials.journal-tabs')
                @else
                    <nav class="journal-tabs" aria-label="Journal sections">
                        @foreach (['about' => 'About', 'current_issue' => 'Current Issue', 'archive' => 'Archive', 'editorial_board' => 'Editorial Panel'] as $key => $label)
                            <a class="{{ $view === $key ? 'active' : '' }}"
                                href="{{ route('journal', $slug) }}?view={{ $key }}">{{ $label }}</a>
                        @endforeach
                    </nav>
                @endif
                <section class="paper-card tab-content-card">
                    <div class="paper-body">
                        @if ($view === 'empty')
                            <div class="empty">No pages are published for this journal yet.</div>
                        @elseif($view === 'content')
                            <h2 class="content-heading">{{ $activePage['title'] }}</h2>
                            <div class="rich-copy policy-copy">{!! \App\Support\JournalPageHtml::clean($activePage['content'] ?? '') !!}</div>
                        @elseif($view === 'about')
                            <h2 class="content-heading">Aim And Scope</h2>
                            <div class="rich-copy">{!! $journal['aim_and_scope'] ?? '<p>Aim and scope information is currently unavailable.</p>' !!}</div>
                        @elseif($view === 'current_issue')
                            @if ($latestIssue)
                                <a class="btn btn-outline-primary btn-sm"
                                    href="{{ route('articles', $latestIssue['issue_id'] ?? $latestIssue['id']) }}">{{ $latestVolume['name'] ?? ($latestIssue['volume'] ?? 'Volume') }}
                                - {{ $latestIssue['issues'] ?? ($latestIssue['name'] ?? 'Issue') }}</a>@else<div
                                    class="empty">No current issue has been published for this journal yet.</div>
                            @endif
                        @elseif($view === 'archive')
                            @forelse(collect($volumes)->sortByDesc(fn($volume) => (int) ($volume['year'] ?? 0)) as $volume)
                                @php $volumeIssues=collect($issues)->filter(fn($i)=>(string)($i['v_id']??'')===(string)($volume['id']??'')||($volume['name']??null)===($i['volume']??null)); @endphp<section class="archive-volume">
                                    <h2>{{ $volume['year'] ?? '' }}</h2>
                                    @forelse($volumeIssues as $issue)
                                        <a class="btn btn-outline-primary btn-sm"
                                            href="{{ route('articles', $issue['issue_id'] ?? $issue['id']) }}">{{ $volume['name'] ?? ($issue['volume'] ?? 'Volume') }},
                                        {{ $issue['issues'] ?? ($issue['name'] ?? 'Issue') }}</a>@empty<div
                                            class="text-muted small px-2">No issues in this volume.</div>
                                    @endforelse
                                </section>
                            @empty<div class="empty">No archive issues are available yet.</div>
                            @endforelse
                        @elseif($view === 'editorial_board')
                            @foreach (['chief' => 'Chief Editor', 'ass' => 'Editorial Board member'] as $type => $heading)
                                <section class="editor-group">
                                    <h2>{{ $heading }}</h2>
                                    @forelse(collect($editors)->where('type',$type) as $editor)
                                        <article class="editor-card">
                                            <div class="row">
                                                <div class="col-md-3"><img
                                                        src="{{ asset_url('editors/' . ($editor['image'] ?? '')) }}"
                                                        alt="{{ $editor['name'] ?? 'Editor' }}"></div>
                                                <div class="col-md-9"><strong>{{ $editor['name'] ?? '' }}</strong>
                                                    <div>{{ $type === 'chief' ? 'Editor-in-Chief' : 'Editor' }}</div>
                                                    <p>{{ $editor['details'] ?? '' }}</p>
                                                    @if ($clean($editor['profile'] ?? null))
                                                        <div>Profile Link: <a href="{{ $editor['profile'] }}"
                                                                target="_blank" rel="noopener">{{ $editor['profile'] }}</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($clean($editor['biography'] ?? null))
                                                <h3>Biography</h3>
                                                <p>{{ $editor['biography'] }}</p>
                                                @endif @if ($clean($editor['interest'] ?? null))
                                                    <h3>Research Interest</h3>
                                                    <p>{{ $editor['interest'] }}</p>
                                                @endif
                                        </article>
                                    @empty<div class="empty">No {{ strtolower($heading) }} records are available.
                                        </div>
                                    @endforelse
                                </section>
                            @endforeach
                        @endif
                    </div>
                </section>
                @include('pages.article-list', ['heading' => 'Latest Articles', 'articles' => $articles])
            </main>
            <aside class="col-lg-3">
                <div class="paper-card journal-cover-card">
                    <div class="paper-body">
                        <img class="journal-cover" src="{{ asset_url('journal/' . ($journal['photo'] ?? '')) }}"
                            alt="{{ $journal['j_name'] ?? 'Journal' }} cover">
                    </div>
                </div>
                @include('pages.journal-sidebar')
                @include('pages.sidebar', [
                'journals' => $journals,
                'editors' => null,
                'indexings' => null,
            ])
            </aside>
        </div>
    </div>
@endsection
