<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\Seo;

class ReadArticleController extends Controller
{
    public function show(string $slug)
    {
        $article = DB::table('article')->where('slug', $slug)->where('status', 1)->first();
        abort_unless($article, 404);
        $issue = DB::table('issues')->where('id', $article->i_id)->first();
        $volume = $issue ? DB::table('volume')->where('id', $issue->v_id)->first() : null;
        $journal = $volume ? DB::table('journals')->where('j_id', $volume->j_id)->first() : null;
        abort_unless($issue && $volume && $journal, 404);

        $citationAuthors = Schema::hasTable('article_authors')
            ? DB::table('article_authors')->where('article_id', $article->id)->orderBy('position')->get()
                ->map(fn ($author) => ['name' => trim($author->first_name.' '.$author->last_name), 'affiliation' => $author->affiliation])->all()
            : [];
        if (!$citationAuthors) {
            $citationAuthors = array_map(fn ($name) => ['name' => Seo::authorName($name), 'affiliation' => null], explode(',', $article->aname ?? ''));
        }
        $citationAuthors = array_values(array_filter($citationAuthors, fn ($author) => $author['name'] !== ''));
        $description = \Illuminate\Support\Str::limit(Seo::text($article->abstract), 160, '.');
        return view('pages.read-article', compact('article', 'issue', 'volume', 'journal', 'citationAuthors', 'description') + ['title' => $article->name]);
    }

    public function fulltext(string $slug)
    {
        $article = DB::table('article')->where('slug', $slug)->where('status', 1)->first();
        abort_unless($article, 404);
        return $this->download((string) $article->id, false);
    }

    public function download(string $id, bool $countDownload = true)
    {
        $article = DB::table('article')->where('id', $id)->where('status', 1)->first();
        abort_unless($article, 404);
        $filename = (string) ($article->file ?? '');
        abort_unless($filename !== '' && basename($filename) === $filename && !str_contains($filename, '\\') && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'pdf', 404);
        foreach ([public_path('assets/articles'), base_path('../backend/public/assets/articles')] as $directory) {
            $root = realpath($directory);
            $path = realpath($directory.'/'.$filename);
            if ($root && $path && str_starts_with($path, $root.DIRECTORY_SEPARATOR) && is_file($path)) {
                if ($countDownload) DB::table('article')->where('id', $article->id)->where('status', 1)->update(['count' => DB::raw('COALESCE(count, 0) + 1')]);
                $headers = ['Content-Type' => 'application/pdf', 'Link' => '<'.Seo::url('/viewArticle/'.rawurlencode($article->slug)).'>; rel="canonical"'];
                return $countDownload ? response()->download($path, $filename, $headers) : response()->file($path, $headers);
            }
        }
        // This base is administrator-configured, never supplied by the visitor.
        $base = rtrim((string) env('ASSET_BASE_URL'), '/');
        abort_unless(filter_var($base, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($base, PHP_URL_SCHEME) ?? ''), ['http', 'https']), 404);
        return redirect()->away($base.'/assets/articles/'.rawurlencode($filename));
    }
}
