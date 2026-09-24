<section class="paper-card">
    <div class="section-title">{{ $heading }}</div>
    <div class="paper-body">
        @forelse($articles as $article)
            <article class="article">
                <div class="article-topline"><span class="badge text-bg-success">Original Article</span><span
                        class="article-count">{{ $article['count'] ?? 0 }} downloads</span></div><a
                    class="article-title d-block mt-3" target="_blank" rel="noopener"
                    href="{{ !empty($article['slug']) ? route('article.show', $article['slug']) : route('journals') }}">{{ $article['name'] ?? 'Research article' }}</a>
                <div class="article-meta flex-column align-items-start gap-1"><span><i class="fa-solid fa-user"></i>
                        {{ author_text($article['aname'] ?? '') }}</span><span><i class="fa-solid fa-bookmark"></i>
                        {{ author_text($article['designation'] ?? '') }}</span><span><i
                            class="fa-solid fa-book-open"></i> Page: {{ $article['page'] ?? '—' }}</span></div>
                @if (!empty($article['doi']) && !in_array($article['doi'], ['undefined', 'null']))
                    <div class="article-doi"><i class="fa-solid fa-tag"></i> DOI: <a href="{{ $article['doi'] }}"
                            target="_blank" rel="noopener">{{ $article['doi'] }}</a></div>
                @endif
                <div class="article-actions">
                    <button class="btn btn-secondary btn-sm" data-bs-toggle="collapse"
                        data-bs-target="#abstract-{{ $article['id'] ?? $loop->index }}"><i
                            class="fa-solid fa-file-lines"></i> Abstract</button>
                    @if (!empty($article['file']) && isset($article['id']) && ctype_digit((string) $article['id']))
                        <a class="btn btn-secondary btn-sm"
                            href="{{ route('article.pdf', ['id' => $article['id'], 'filename' => (substr(\Illuminate\Support\Str::slug(strip_tags($article['name'] ?? '')), 0, 150) ?: 'article') . '.pdf']) }}"
                        target="_blank" rel="noopener"><i class="fa-solid fa-file-pdf"></i> PDF</a>@else<span
                            class="btn btn-secondary btn-sm disabled" aria-disabled="true">PDF unavailable</span>
                    @endif
                    <a class="btn btn-success btn-sm" target="_blank" rel="noopener"
                        href="{{ !empty($article['slug']) ? route('article.show', $article['slug']) : route('journals') }}">
                        View Article <i class="fa-solid fa-circle-arrow-right"></i></a>
                </div>
                <div class="collapse abstract-box" id="abstract-{{ $article['id'] ?? $loop->index }}">
                    {!! $article['abstract'] ?? '' !!}</div>
        </article>@empty<div class="empty">No articles are available yet.</div>
        @endforelse
    </div>
</section>
