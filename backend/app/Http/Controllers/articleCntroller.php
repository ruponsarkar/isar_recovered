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
        $pre_count= DB::table('article')->where('id', $id)->first()->count;
        $count = DB::table('article')->where('id', $id)->update([
            'count'=> $pre_count + 1
        ]);
        $journal = DB::table('article')->where('id', $id)->first()->file;
        $path='/assets/articles/'.$journal;
        return response()->download(public_path($path));
        
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
