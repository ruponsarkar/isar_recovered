<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    @include('partials.seo')
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('site.css') }}" rel="stylesheet">
    <link href="{{ asset('journal.css') }}" rel="stylesheet">
    @stack('head')
    <link href="{{ asset('theme.css') }}" rel="stylesheet">
</head>

<body class="public-site @yield('body-class')">@include('partials.nav')<a class="manu-btn" href="{{ route('manuscript', ['journal' => data_get($journal ?? null, 'slug') ?: data_get($journal ?? null, 'j_id')]) }}"><i
            class="fa-solid fa-paper-plane"></i> Submit Manuscript</a>
    <main>
        @if (session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button
                        class="btn-close" data-bs-dismiss="alert"></button></div>
            </div>
        @endif @yield('content')
    </main>@include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>@stack('scripts')
</body>

</html>
