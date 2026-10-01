<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class JournalPages
{
    public static function href(array $page, string $journalSlug): string
    {
        if ($page['type'] !== 'link') return route('journal.page', [$journalSlug, $page['slug']]);
        if (in_array($page['link_url'] ?? '', ['/action', '/contact-us'], true)) return route('contact', ['journal' => $journalSlug]);
        return \App\Support\JournalPageLink::valid($page['link_url'] ?? null) ? $page['link_url'] : '#';
    }

    /** null means the page migration has not yet been deployed on a local installation. */
    public function published(array $journal, bool $remote): ?array
    {
        if ($remote) {
            $base = rtrim((string) config('services.public_api.base_url', env('PUBLIC_API_BASE_URL')), '/');
            try {
                $response = Http::connectTimeout(5)->timeout(20)->acceptJson()->get($base.'/journals/'.rawurlencode($journal['slug']).'/pages');
                $pages = $response->json('pages');
                abort_unless($response->successful() && is_array($pages), 503, 'Journal pages are temporarily unavailable.');
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                abort(503, 'Journal pages are temporarily unavailable.');
            }
        } else {
            if (!Schema::hasTable('journal_pages')) return null;
            $pages = DB::table('journal_pages')->where('journal_id', $journal['j_id'])->where('is_published', true)->orderBy('sort_order')->orderBy('id')->get()->map(fn ($page) => (array) $page)->all();
        }
        // Recheck the boundary even when consuming the API; drafts and foreign rows never render.
        $pages = array_values(array_filter($pages, fn ($page) => !empty($page['is_published']) && (string) ($page['journal_id'] ?? '') === (string) $journal['j_id']));
        $groups = collect($pages)->where('type', 'group')->whereNull('parent_id')->pluck('id')->map(fn ($id) => (string) $id)->all();
        $pages = array_values(array_filter($pages, fn ($page) => empty($page['parent_id']) || in_array((string) $page['parent_id'], $groups, true)));
        usort($pages, fn ($a, $b) => [(int) $a['sort_order'], (int) $a['id']] <=> [(int) $b['sort_order'], (int) $b['id']]);
        return $pages;
    }
}
