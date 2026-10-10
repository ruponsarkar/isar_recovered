<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class articleCntroller extends Controller
{
    //
    function viewArticle($slug){

        // $string = str_replace(' ', '-', $slug);
        // $main = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        // $articles = DB::table('article')->get();

        // foreach($articles as $data){
        //     $name = $data->name;
        //     $string = str_replace(' ', '-', $name);
        //     $main = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        //     $update = DB::table('article')->where('id', $data->id)->update([
        //         'slug'=> $main
        //     ]);

        // }

        
        $article = DB::table('article')
        ->join('issues', 'issues.id', '=', 'article.i_id')
        ->join('volume', 'volume.id', '=', 'issues.v_id')
        ->join('journals', 'journals.j_id', '=', 'volume.j_id')
        ->where('article.slug', $slug)
        ->where('article.status', 1)
        ->select('*', 'article.name as name', 'article.id as airticle_id')
        ->first();

        return view('rupon.viewArticle', ['article'=> $article]);
    }

    function download($id){
        $article = DB::table('article')->where('id', $id)->where('status', 1)->first();
        abort_unless($article, 404);
        $filename = (string) $article->file;
        abort_unless($filename !== '' && basename($filename) === $filename && !str_contains($filename, '\\'), 404);
        $root = realpath(public_path('assets/articles'));
        $path = realpath(public_path('assets/articles/' . $filename));
        abort_unless($root && $path && str_starts_with($path, $root . DIRECTORY_SEPARATOR) && is_file($path), 404);
        DB::table('article')->where('id', $id)->update(['count' => DB::raw('COALESCE(count, 0) + 1')]);
        $name = (substr(\Illuminate\Support\Str::slug(strip_tags((string) $article->name)), 0, 150) ?: 'article') . '.pdf';
        return response()->download($path, $name, ['Content-Type' => 'application/pdf'], (request()->boolean('inline') || request()->routeIs('article.pdf')) ? 'inline' : 'attachment');

    }


    function forceDelete($filename){
        // return $filename;
        // $delete = DB::table('article')->where('file', $filename)->get();

        $filePath = public_path('assets/articles/' . $filename);

        if (file_exists($filePath)) {
            unlink($filePath);

            return response()->json(['message' => 'Permanently deleted the file']);
        }
        else{
            return response()->json(['message' => 'File not found']);
        }

    }
}
