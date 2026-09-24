@extends('layouts.app')
@section('body-class', 'home-page')
@push('head')
    <link href="{{ asset('home.css') }}" rel="stylesheet">
@endpush
@section('content')
    @php
        $fallback = [
            ['cover' => 'journals/j1.jpg', 'name' => 'ISAR Journal of Arts, Humanities and Social Sciences'],
            ['cover' => 'journals/j2.jpg', 'name' => 'ISAR Journal of Science and Technology'],
            ['cover' => 'journals/j3.jpg', 'name' => 'ISAR Journal of Economics and Business Management'],
        ];
        $hero = count($journals) ? $journals : $fallback;
        $latest = count($articles)
            ? array_slice($articles, 0, 5)
            : [
                [
                    'name' => 'A peer-reviewed platform for research and scholarly communication',
                    'aname' => 'ISAR Publisher Editorial Office',
                    'page' => 'Online first',
                ],
                [
                    'name' => 'Research discovery across science, technology, arts and management',
                    'aname' => 'International Academic Community',
                    'page' => 'Open access',
                ],
            ];
    @endphp
    <div class="container mt-2">
        <div class="row">
            <aside class="col-lg-3 d-none d-lg-block">@include('pages.sidebar', [
                'journals' => $journals,
                'editors' => $editors,
                'indexings' => $indexings,
            ])</aside>
            <section class="col-lg-9">
                <article class="paper-card journal-hero">
                    <div class="section-title d-flex justify-content-between"><span>Journals</span><a class="hero-all-link"
                            href="{{ route('journals') }}">View all <i class="fa-solid fa-arrow-right"></i></a></div>
                    <div class="paper-body">
                        <div id="journalCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3200">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="journal-carousel-track">
                                        @foreach ($hero as $j)
                                            <a class="journal-hero-cover"
                                                href="{{ count($journals) ? route('journal', $j['slug'] ?? ($j['j_id'] ?? 'journal')) : route('journals') }}"><img
                                                    src="{{ asset_url('journal/' . ($j['photo'] ?? $j['cover'])) }}"
                                                    alt="{{ $j['j_name'] ?? $j['name'] }}"><span>{{ $j['j_name'] ?? $j['name'] }}</span></a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!count($journals))
                            <div class="hero-fallback-note"><i class="fa-solid fa-circle-info"></i> Explore our publication
                                collection while the live catalogue reconnects. <a href="{{ route('journals') }}">Browse
                                    journals</a></div>
                        @endif
                    </div>
                </article>
                <article class="paper-card narrative-card">
                    <div class="section-title">Mission and Vision</div>
                    <div class="paper-body">
                        <div class="narrative-kicker">Advancing trusted, accessible scholarship</div>
                        <div class="text-uppercase narrative-heading">Our Mission</div>
                        <p>Our mission is to provide academicians, researchers, and scholars with up-to-date information on
                            discoveries, developments, and ongoing activities in the fields of science and technology. We
                            aim to make this information easily accessible, enabling individuals in these domains to stay
                            informed effortlessly.</p>
                        <div class="text-uppercase narrative-heading">Our Vision</div>
                        <p>At ISAR Publisher, our vision is to uphold the highest standards of quality in the publication of
                            research and review articles. We are deeply committed to the timely and effective dissemination
                            of peer-reviewed, top-quality work. To achieve this, we have assembled a team of esteemed
                            editorial members representing diverse disciplines and specializations. Their dedication ensures
                            an efficient and constructive review process that aligns with international research publishing
                            standards.</p>
                        <p>Through our mission and vision, we aspire to be a trusted source for the academic and research
                            community, facilitating the exchange of knowledge and contributing to the advancement of science
                            and technology.</p>
                    </div>
                </article>
                <article class="paper-card aim-card">
                    <div class="section-title">Aim and Objective</div>
                    <div class="paper-body">International Scientific and Academic Research Publisher is an international
                        publisher committed to providing a <em>peer-reviewed</em> platform to outstanding researchers and
                        scientists to exhibit their findings for the furtherance of society, to provoke debate, and to
                        provide an educational forum.<br><br>Its aim is to serve scholars and researchers around the world
                        through fast publication and the widest exposure by providing free access. The International
                        Scientific and Academic Research Publishers team works with dedication to improve its services and
                        maintain high quality standards.</div>
                </article>
                <article class="paper-card latest-card">
                    <div class="section-title">Latest Articles</div>
                    <div class="paper-body">
                        @foreach ($latest as $article)
                            <div class="article editorial-article">
                                <div class="article-topline"><span class="badge text-bg-success">Original
                                        Article</span><span class="article-count"><i class="fa-solid fa-download"></i>
                                        {{ $article['count'] ?? 'Open access' }}</span></div>
                                <div class="article-title mt-2">{{ $article['name'] ?? $article['title'] }}</div>
                                <div class="article-meta flex-column align-items-start"><span><i class="fa-regular fa-user"></i>
                                        {{ author_text($article['aname'] ?? ($article['author'] ?? 'ISAR Research Author')) }}</span>
                                        <span class="article-affiliations w-100"><i
                                            class="fa-solid fa-bookmark"></i>
                                        {{ author_text($article['designation'] ?? 'Peer-reviewed publication') }}</span>
                                </div>
                                @if (!empty($article['doi']) && $article['doi'] !== 'null')
                                    <div class="article-doi">DOI: <a
                                            href="{{ $article['doi'] }}">{{ $article['doi'] }}</a></div>
                                @endif
                                <div class="article-actions">
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#abstract{{ $loop->index }}"><i
                                            class="fa-regular fa-file-lines"></i> Abstract</button>
                                    @if (!empty($article['file']) && isset($article['id']) && ctype_digit((string) $article['id']))
                                        <a class="btn btn-outline-secondary btn-sm"
                                            href="{{ route('article.pdf', ['id' => $article['id'], 'filename' => (substr(\Illuminate\Support\Str::slug(strip_tags($article['name'] ?? '')), 0, 150) ?: 'article') . '.pdf']) }}"><i
                                                class="fa-solid fa-file-pdf"></i> PDF</a>
                                    @endif
                                    <a class="btn btn-isar btn-sm"
                                        href="{{ !empty($article['slug']) ? route('article.show', $article['slug']) : route('journals') }}">
                                        Read Article <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                                <div class="collapse" id="abstract{{ $loop->index }}">
                                    <div class="abstract-box">{!! $article['abstract'] ??
                                        'Article abstract and full publication details are available through the journal archive.' !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>
        </div>
    </div>
    <section class="metric-band">
        <div class="container">
            <div class="metric-intro"><span>ISAR at a glance</span>
                <p>A growing international home for research, review and discovery.</p>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="metric"><i class="fa-solid fa-book-open"></i><strong class="counter"
                            data-target="{{ max(count($journals), 50) }}">0</strong>Journals</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric"><i class="fa-solid fa-file-lines"></i><strong class="counter"
                            data-target="{{ max(count($articles), 1000) }}">0</strong>Articles</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric"><i class="fa-solid fa-users"></i><strong class="counter"
                            data-target="{{ max(count($editors), 500) }}">0</strong>Editorial Members</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric"><i class="fa-solid fa-globe"></i><strong class="counter"
                            data-target="100">0</strong>Countries</div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.querySelectorAll('.counter').forEach(c => {
            let o = new IntersectionObserver(e => e.forEach(x => {
                if (!x.isIntersecting || c.dataset.done) return;
                c.dataset.done = 1;
                let t = +c.dataset.target,
                    n = 0,
                    i = setInterval(() => {
                        n += Math.ceil(t / 60);
                        if (n >= t) {
                            n = t;
                            clearInterval(i)
                        }
                        c.textContent = n + (t >= 50 ? '+' : '')
                    }, 20)
            }), {
                threshold: .5
            });
            o.observe(c)
        });
    </script>
@endpush
