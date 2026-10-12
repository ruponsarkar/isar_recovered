@extends('layouts.app') @section('content')
    <div class="container mt-3">
        <article class="paper-card">
            <div class="section-title">All Journals</div>
            <div class="paper-body">
                <div class="row">
                    @forelse($journals as $j)
                        <div class="col-md-6 mb-3">
                            <div class="border h-100 p-2">
                                <div class="row h-100">
                                    <div class="col-5 d-flex align-items-center"><img class="journal-cover"
                                            src="{{ backend_asset_url('journal/' . ($j['photo'] ?? 'journals/j1.jpg')) }}"
                                            alt="Journal cover"></div>
                                    <div class="col-7">
                                        <div class="journal-name small">{{ $j['j_name'] ?? 'ISAR Journal' }}</div>
                                        <small><b>Abbr. Title:</b> {{ $j['abbr_title'] ?? '—' }}<br><b>ISSN (Online):</b>
                                            {{ $j['issn'] ?? '—' }}<br><b>Publisher:</b> ISAR Publisher<br><b>Language:</b>
                                            English</small>
                                        <div class="text-end mt-2"><a class="btn btn-info btn-sm"
                                                href="{{ route('journal', $j['slug'] ?? ($j['j_id'] ?? 'journal')) }}">Read
                                                More</a></div>
                                    </div>
                                </div>
                            </div>
                    </div>@empty<div class="empty">No journals are currently available. Configure database credentials
                            or <code>PUBLIC_API_BASE_URL</code> to load the existing catalogue.</div>
                    @endforelse
                </div>
            </div>
        </article>
    </div>
@endsection
