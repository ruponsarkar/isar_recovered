<nav class="navbar navbar-expand-lg navbar-dark isar-nav sticky-top" aria-label="Journal navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('journal', $slug) }}">{{ ($journal['abbr_title'] ?? '') ?: ($journal['j_name'] ?? 'Journal') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#journalNav" aria-controls="journalNav" aria-expanded="false" aria-label="Toggle journal navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="journalNav">
            <ul class="navbar-nav ms-auto">
                @foreach(collect($journalPages)->whereNull('parent_id')->where('show_in_nav', true) as $entry)
                    @if($entry['type'] === 'group')
                        @php $children = collect($journalPages)->where('parent_id', $entry['id'])->where('show_in_nav', true); @endphp
                        @if($children->isNotEmpty())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ ($activePage['parent_id'] ?? null) == $entry['id'] ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $entry['title'] }}</a>
                                <ul class="dropdown-menu">
                                    @foreach($children as $child)
                                        <li><a class="dropdown-item {{ ($activePage['id'] ?? null) == $child['id'] ? 'active' : '' }}" href="{{ \App\Services\JournalPages::href($child, $slug) }}" @if(($activePage['id'] ?? null) == $child['id']) aria-current="page" @endif>{{ $child['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link {{ ($activePage['id'] ?? null) == $entry['id'] ? 'active' : '' }}" href="{{ \App\Services\JournalPages::href($entry, $slug) }}" @if(($activePage['id'] ?? null) == $entry['id']) aria-current="page" @endif>{{ $entry['title'] }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
