<?php

namespace App\Http\Controllers;

use App\Services\Seo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    private const SIZE = 1000;

    private function query(string $type)
    {
        if ($type === 'journal-pages') {
            return DB::table('journal_pages as pages')->join('journals', 'journals.j_id', '=', 'pages.journal_id')
                ->leftJoin('journal_pages as parent', 'parent.id', '=', 'pages.parent_id')
                ->where('pages.is_published', true)->where('pages.type', 'content')->where('pages.slug', '<>', 'about')
                ->where(function ($query) {
                    $query->whereNull('pages.parent_id')->orWhere(function ($parent) {
                        $parent->whereColumn('parent.journal_id', 'pages.journal_id')->where('parent.type', 'group')->where('parent.is_published', true);
                    });
                })->select('pages.*', 'journals.slug as journal_slug')->orderBy('pages.id');
        }
        if ($type === 'journals') {
            return DB::table('journals')->whereNotNull('slug')->where('slug', '<>', '')->orderBy('j_id');
        }
        $query = DB::table('issues')->join('volume', 'volume.id', '=', 'issues.v_id')
            ->join('journals', 'journals.j_id', '=', 'volume.j_id');
        if ($type === 'issues') {
            return $query->select('issues.*')->orderBy('issues.id');
        }
        return $query->join('article', 'article.i_id', '=', 'issues.id')
            ->where('article.status', 1)->whereNotNull('article.slug')->where('article.slug', '<>', '')
            ->select('article.*')->orderBy('article.id');
    }

    public function index()
    {
        $entries = [Seo::url('/sitemap-pages.xml')];
        $types = ['journals', 'issues', 'articles'];
        if (Schema::hasTable('journal_pages')) $types[] = 'journal-pages';
        foreach ($types as $type) {
            $count = $this->query($type)->count();
            for ($page = 1; $page <= (int) ceil($count / self::SIZE); $page++) {
                $entries[] = Seo::url("/sitemap-$type-$page.xml");
            }
        }
        return response()->view('seo.sitemap-index', compact('entries'))->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function pages()
    {
        $paths = ['/', '/AllJournals', '/action', '/open-access-policy', '/CopyrightPolicyPage', '/author-instruction',
            '/processingFees', '/reviewProcess', '/aboutUs', '/RefundPolicy', '/PublicationFee', '/ReviewGuidlines',
            '/EditorGuidlines', '/EthicsPolicies', '/Representatives', '/GenerativeAIPolicy'];
        return $this->xml(array_map(fn ($path) => ['loc' => Seo::url($path)], $paths));
    }

    public function records(string $type, string $page)
    {
        abort_unless(in_array($type, ['journals', 'issues', 'articles', 'journal-pages'], true) && (int) $page > 0, 404);
        abort_if($type === 'journal-pages' && !Schema::hasTable('journal_pages'), 404);
        $rows = $this->query($type)->forPage((int) $page, self::SIZE)->get();
        abort_if($rows->isEmpty(), 404);
        $entries = [];
        $journalPages = $type === 'journals' && Schema::hasTable('journal_pages')
            ? DB::table('journal_pages')->whereIn('journal_id', $rows->pluck('j_id'))->where('is_published', true)->get() : null;
        foreach ($rows as $row) {
            $path = $type === 'journals' ? '/journal/'.rawurlencode($row->slug)
                : ($type === 'issues' ? '/Articles/'.$row->id : '/viewArticle/'.rawurlencode($row->slug));
            if ($type === 'journal-pages') $path = '/journal/'.rawurlencode($row->journal_slug).'/pages/'.rawurlencode($row->slug);
            $entry = ['loc' => Seo::url($path)];
            // Only genuine stored modification/publication dates, never the sitemap generation time.
            $date = $row->updated_at ?? ($type === 'articles' ? ($row->published_date ?? null) : null);
            if ($date && preg_match('/^\d{4}-\d{2}-\d{2}/', $date)) {
                $day = substr($date, 0, 10);
                [$y, $m, $d] = array_map('intval', explode('-', $day));
                if (checkdate($m, $d, $y) && $day <= date('Y-m-d')) $entry['lastmod'] = $day;
            }
            $entries[] = $entry;
            if ($type === 'journals') {
                foreach (['about', 'archive', 'editorial_board'] as $tab) {
                    if ($journalPages !== null) {
                        $own = $journalPages->where('journal_id', $row->j_id);
                        $available = $own->filter(fn ($entry) => !$entry->parent_id || $own->where('type', 'group')->contains('id', $entry->parent_id));
                        $exists = $tab === 'about' ? $available->where('type', 'content')->contains('slug', 'about') : $available->where('type', 'system')->contains('system_key', $tab);
                        if (!$exists) continue;
                    }
                    $entries[] = ['loc' => Seo::url($path.'?view='.$tab)];
                }
            }
        }
        return $this->xml($entries);
    }

    private function xml(array $entries)
    {
        return response()->view('seo.sitemap', compact('entries'))->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots()
    {
        return response("User-agent: *\nAllow: /\nDisallow: /pay/\nDisallow: /PaymentDetails\nSitemap: ".Seo::url('/sitemap.xml')."\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
