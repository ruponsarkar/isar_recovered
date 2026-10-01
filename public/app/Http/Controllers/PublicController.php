<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\PublicSidebarData;
use App\Services\JournalPages;

class PublicController extends Controller
{
    private function api(string $path): array
    {
        $base = rtrim((string) config('services.public_api.base_url'), '/');
        if (!$base)
            return [];
        try {
            $json = Http::connectTimeout(5)->timeout(20)->acceptJson()->get("$base/$path")->json() ?: [];
            return isset($json['data']) && is_array($json['data']) ? $json['data'] : $json;
        } catch (\Throwable $e) {
            return [];
        }
    }
    private function rows(string $table, ?string $column = null, $value = null): array
    {
        try {
            $q = DB::table($table);
            if ($column)
                $q->where($column, $value);
            return $q->get()->map(fn($r) => (array) $r)->all();
        } catch (\Throwable $e) {
            return [];
        }
    }
    private function first(string $table, string $column, $value): array
    {
        try {
            return (array) (DB::table($table)->where($column, $value)->first() ?: []);
        } catch (\Throwable $e) {
            return [];
        }
    }
    private function globals(): array
    {
        return app(PublicSidebarData::class)->get();
    }
    public function home()
    {
        $articles = $this->rows('article') ?: ($this->api('getJournals')['latest_artricle'] ?? []);

        usort($articles, fn($a, $b) => (int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0));

        return view('pages.home', $this->globals() + ['articles' => array_slice($articles, 0, 5)]);
    }
    public function journals()
    {
        return view('pages.journals', $this->globals());
    }
    public function journalPage(string $journal, string $page, Request $request)
    {
        return $this->journal($journal, $request, $page);
    }

    public function journal(string $journal, Request $request, ?string $page = null)
    {
        $local = $this->first('journals', 'slug', $journal);
        if (!$local && ctype_digit($journal))
            $local = $this->first('journals', 'j_id', $journal);
        $api = $this->api("journalDetails/$journal");
        $remote = !empty($api['journal']);
        $record = $remote ? $api['journal'] : $local;
        abort_unless($record, 404);
        $journalPages = app(JournalPages::class)->published($record, $remote);
        $activePage = null;
        if ($journalPages !== null) {
            $readable = collect($journalPages)->where('type', '!=', 'group');
            if ($page !== null) {
                $activePage = $readable->firstWhere('slug', $page);
                abort_unless($activePage, 404);
            } elseif ($request->has('view')) {
                $legacy = $request->query('view');
                $activePage = $legacy === 'about' ? $readable->firstWhere('slug', 'about') : $readable->firstWhere('system_key', $legacy);
                abort_unless($activePage, 404);
            } else {
                $localPages = $readable->where('type', '!=', 'link');
                $activePage = $localPages->firstWhere('system_key', 'current_issue') ?? $localPages->firstWhere('show_in_tabs', true) ?? $localPages->first();
            }
        } else {
            abort_if($page !== null, 404);
        }
        if (($activePage['type'] ?? null) === 'link') {
            abort_unless(\App\Support\JournalPageLink::valid($activePage['link_url'] ?? null), 404);
            return redirect()->away($activePage['link_url']);
        }
        $jid = $record['j_id'] ?? null;
        $filter = fn(array $rows) => $jid === null ? $rows : array_values(array_filter($rows, fn($r) => (string) ($r['j_id'] ?? '') === (string) $jid));
        $volumes = $remote ? ($api['volume'] ?? []) : $filter($this->rows('volume'));
        $volumeIds = collect($volumes)->pluck('id')->map(fn($id) => (string) $id)->all();
        $localIssues = array_values(array_filter($this->rows('issues'), fn($r) => in_array((string) ($r['v_id'] ?? ''), $volumeIds, true)));
        $issues = $remote ? ($api['issues'] ?? []) : $localIssues;
        $editors = $remote ? ($api['editors'] ?? []) : array_values(array_filter($filter($this->rows('editors_data')), fn($r) => !isset($r['active']) || (int) $r['active'] === 1));
        $issueIds = collect($issues)->map(fn($r) => (string) ($r['issue_id'] ?? $r['id'] ?? ''))->all();
        $localArticles = array_values(array_filter($this->rows('article'), fn($r) => in_array((string) ($r['i_id'] ?? ''), $issueIds, true) && (!isset($r['status']) || (int) $r['status'] === 1)));
        usort($localArticles, fn($a, $b) => (int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0));
        $articles = $remote ? ($api['latest_journal_article'] ?? []) : array_slice($localArticles, 0, 5);
        $ia = $this->api("getIndexingById/$journal");
        $ca = $this->api("getCeertificateById/$journal");
        $fa = $this->api("getImpactFactor/$journal");
        $factors = collect($remote ? ($fa['impact'] ?? []) : $filter($this->rows('impact_factors')))->filter(fn($factor) => (string) ($factor['isActive'] ?? '') === '1')->values()->all();
        $tab = $request->query('view', 'current_issue');
        if (!in_array($tab, ['about', 'current_issue', 'archive', 'editorial_board'], true))
            $tab = 'current_issue';
        if ($journalPages !== null) $tab = $activePage ? ($activePage['type'] === 'system' ? $activePage['system_key'] : 'content') : 'empty';
        return view('pages.journal', ['journalPages' => $journalPages, 'activePage' => $activePage, 'journal' => $record, 'slug' => $record['slug'] ?? $journal, 'articles' => $articles, 'editors' => $editors, 'issues' => $issues, 'volumes' => $volumes, 'indexings' => $remote ? ($ia['indexing'] ?? $api['indexing'] ?? []) : $filter($this->rows('indexing')), 'certificates' => $remote ? ($ca['certificate'] ?? []) : $filter($this->rows('certificate')), 'factors' => $factors, 'chief' => collect($editors)->firstWhere('type', 'chief') ?: ($api['chief'] ?? null), 'view' => $tab] + $this->globals());
    }
    public function articles(string $articles)
    {
        $api = $this->api("getArticle/$articles");
        $issue = $this->first('issues', 'id', $articles) ?: ($api['getIssue'] ?? []);
        $rows = $this->rows('article', 'i_id', $articles) ?: ($api['article'] ?? []);
        $rows = array_values(array_filter($rows, fn($r) => !isset($r['status']) || (int) $r['status'] === 1));
        $volume = (!empty($issue['v_id']) ? $this->first('volume', 'id', $issue['v_id']) : []) ?: ($api['getVol'] ?? []);
        $remote = !empty($api['getJournal']);
        $journal = $remote ? $api['getJournal'] : (!empty($volume['j_id']) ? $this->first('journals', 'j_id', $volume['j_id']) : []);
        $journalPages = $journal ? app(JournalPages::class)->published($journal, $remote) : null;
        $slug = $journal['slug'] ?? (string) ($journal['j_id'] ?? $articles);
        return view('pages.articles', compact('issue', 'volume', 'journal', 'journalPages', 'slug') + ['articles' => $rows] + $this->globals());
    }
    public function policy(string $page)
    {
        $pages = ['open-access-policy' => ['Open Access Policy', 'open-access'], 'CopyrightPolicyPage' => ['Copyright Policy', 'copyright'], 'author-instruction' => ['Instruction to Authors', 'author-guidelines'], 'processingFees' => ['Processing Fees', 'publication-fee'], 'reviewProcess' => ['Peer Review Policy', 'review-process'], 'aboutUs' => ['About ISAR Publisher', 'about'], 'RefundPolicy' => ['Cancellation / Refund Policy', 'refund'], 'PublicationFee' => ['Publication Fee', 'publication-fee'], 'ReviewGuidlines' => ['Reviewer Guidelines', 'reviewer-guidelines'], 'EditorGuidlines' => ['Editor Guidelines', 'editor-guidelines'], 'EthicsPolicies' => ['Ethics & Policies', 'ethics'], 'Representatives' => ['Representatives', 'representatives'], 'GenerativeAIPolicy' => ['Generative AI Policy', 'generative-ai']];
        abort_unless(isset($pages[$page]), 404);
        return view('pages.policy', $this->globals() + ['title' => $pages[$page][0], 'partial' => $pages[$page][1]]);
    }
    public function contact()
    {
        return view('pages.contact', $this->globals());
    }
}
