{{-- <div class="side-card">
    <div class="section-title">Applications</div><a href="/joinReviewer">Join as Reviewer</a><a href="/joinEditor">Join as
        Editor</a>
</div> --}}
@if (count($factors))
    <div class="side-card">
        <div class="section-title">Impact Factor</div>
        @foreach ($factors as $factor)
            <a class="impact" href="{{ $factor['link'] ?? '#' }}"
                target="_blank"><strong>{{ $factor['impact'] ?? '' }}</strong></a>
        @endforeach
    </div>
    @endif @if (count($indexings))
        <div class="side-card">
            <div class="section-title">Indexing</div>
            <div class="logo-grid">
                @foreach ($indexings as $i)
                    <a href="{{ $i['link'] ?? '#' }}" target="_blank" rel="noopener"><img
                            src="{{ backend_asset_url('indexing/' . ($i['img'] ?? '')) }}"
                            onerror="this.src='{{ backend_asset_url('indexings/' . ($i['img'] ?? '')) }}'"
                            alt="Indexing service"></a>
                @endforeach
            </div>
        </div>@endif @if (count($certificates))
            <div class="side-card">
                <div class="section-title">Certificate</div>
                @foreach ($certificates as $c)
                    <img class="certificate" src="{{ backend_asset_url('certificate/' . ($c['img'] ?? '')) }}"
                        alt="Journal certificate">
                @endforeach
            </div>
        @endif
