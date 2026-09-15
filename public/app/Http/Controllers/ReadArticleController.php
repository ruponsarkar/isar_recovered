<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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

        return view('pages.read-article', compact('article', 'issue', 'volume', 'journal') + ['title' => $article->name]);
    }

    public function download(string $id)
    {
        $article = DB::table('article')->where('id', $id)->where('status', 1)->first();
        abort_unless($article, 404);
        $filename = (string) ($article->file ?? '');
        abort_unless($filename !== '' && basename($filename) === $filename && !str_contains($filename, '\\') && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'pdf', 404);
        foreach ([public_path('assets/articles'), base_path('../backend/public/assets/articles')] as $directory) {
            $root = realpath($directory);
            $path = realpath($directory.'/'.$filename);
            if ($root && $path && str_starts_with($path, $root.DIRECTORY_SEPARATOR) && is_file($path)) {
                DB::table('article')->where('id', $article->id)->where('status', 1)->update(['count' => DB::raw('COALESCE(count, 0) + 1')]);
                return response()->download($path, $filename, ['Content-Type' => 'application/pdf']);
            }
        }
        // This base is administrator-configured, never supplied by the visitor.
        $base = rtrim((string) env('ASSET_BASE_URL'), '/');
        abort_unless(filter_var($base, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($base, PHP_URL_SCHEME) ?? ''), ['http', 'https']), 404);
        return redirect()->away($base.'/assets/articles/'.rawurlencode($filename));
    }
}
