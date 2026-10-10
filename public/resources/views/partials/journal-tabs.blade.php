@php $tabs = collect($journalPages)->where('show_in_tabs', true)->where('type', '!=', 'group'); @endphp
@if($tabs->isNotEmpty())
<nav class="journal-tabs" aria-label="Journal sections">
    @foreach($tabs as $entry)
        <a class="{{ ($activePage['id'] ?? null) == $entry['id'] ? 'active' : '' }}" href="{{ \App\Services\JournalPages::href($entry, $slug) }}" @if(($activePage['id'] ?? null) == $entry['id']) aria-current="page" @endif>{{ $entry['title'] }}</a>
    @endforeach
</nav>
@endif
