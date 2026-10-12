<header>
    <div class="social-strip"><a aria-label="WhatsApp" href="https://wa.me/918638994354"><i
                class="fa-brands fa-whatsapp"></i></a><a aria-label="Facebook"
            href="https://www.facebook.com/profile.php?id=61552592232822"><i class="fa-brands fa-facebook"></i></a><a
            aria-label="Instagram" href="https://www.instagram.com/isarpublisher/"><i
                class="fa-brands fa-instagram"></i></a></div>
    <div class="masthead"><a href="{{ route('home') }}" aria-label="ISAR Publisher home"><img src="{{ backend_asset_url('mainlogo.png') }}" alt="ISAR Publisher"></a>
        <h1>International Scientific and Academic Research Publisher</h1>
    </div>
    @if (isset($journalPages))
        @include('partials.journal-nav')
    @else
        <nav class="navbar navbar-expand-lg navbar-dark isar-nav default-nav sticky-top">
            <div class="container-fluid"><a class="navbar-brand" href="/">ISAR Publisher</a><button
                    class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/AllJournals">Journals</a></li>
                        {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"
                                data-bs-toggle="dropdown">For Authors</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/author-instruction">Author Guidelines</a></li>
                                <li><a class="dropdown-item" href="/joinAuthor">Join Author</a></li>
                                <li><a class="dropdown-item" href="/open-access-policy">Open Access Policy</a></li>
                                <li><a class="dropdown-item" href="/reviewProcess">Review Process</a></li>
                                <li><a class="dropdown-item" href="/Representatives">Representatives</a></li>
                            </ul>
                        </li> --}}
                        {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"
                                data-bs-toggle="dropdown">For Reviewers</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/ReviewGuidlines">Reviewer Guidelines</a></li>
                                <li><a class="dropdown-item" href="/joinReviewer">Join Reviewer</a></li>
                            </ul>
                        </li> --}}
                        {{-- <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"
                                data-bs-toggle="dropdown">For Editors</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/EditorGuidlines">Editor Guidelines</a></li>
                                <li><a class="dropdown-item" href="/joinEditor">Join Editor</a></li>
                            </ul>
                        </li> --}}
                        <li class="nav-item"><a class="nav-link" href="/aboutUs">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Payment">Payment</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                        <li class="nav-item mobile-submit"><a class="nav-link" href="{{ route('manuscript', ['journal' => data_get($journal ?? null, 'slug') ?: data_get($journal ?? null, 'j_id')]) }}">Submit Manuscript</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
</header>
