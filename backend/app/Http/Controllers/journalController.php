<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\journal;
use App\Models\editorsData;
use App\Models\volume;
use App\Models\issue;
use App\Models\article;

use DB;

class journalController extends Controller
{
    //

    function getJournals()
    {
        $journals = journal::orderBy('sl_no', 'ASC')->get();
        // return $journals;

        $latest_artricle = article::latest()->take(5)->where('status', 1)->get();

        $topEditors = DB::table('editors_data')->where('isFeature', 'true')->get();

        $indexings = DB::table('indexing')->orderBy('id', 'desc')->get();
        $indexings_unique = DB::table('indexing')->where('isShow', 1)->orderBy('id', 'desc')->get();



        return response()->json(['journals' => $journals, 'latest_artricle' => $latest_artricle, 'topEditors' => $topEditors, 'indexings' => $indexings, 'indexings_unique' => $indexings_unique, 'status' => 200]);
    }


    function journalDetails($journal)
    {

        // return $journal;
        $id = journal::where('slug', $journal)->first();
        // return $id->j_id;

        if ($id->j_id) {
            $journal = $id->j_id;
        }

        // journal id is used in down query, check once before change it
        $journalData = journal::where('j_id', $journal)->first();

        $issues = DB::table('volume')
            ->join('issues', 'issues.v_id', '=', 'volume.id')
            ->where('volume.j_id', $journal)
            ->select('issues.name as issues', 'issues.id as issue_id', 'volume.name as volume')

            ->get();

        $volume = DB::table('volume')->where('j_id', $journal)
            ->orderBy('id', 'DESC')
            ->get();

        $editors = DB::table('editors_data')->where('j_id', $journal)
            ->where('active', 1)
            ->orderBy('id', 'DESC')
            ->get();

        $chief = DB::table('editors_data')->where('j_id', $journal)->where('active', 1)->where('type', 'chief')->first();

        $latest_artricle = article::latest()->take(5)->where('status', 1)->get();


        $latest_journal_article = DB::table('article')
            ->join('issues', 'issues.id', '=', 'article.i_id')
            ->join('volume', 'volume.id', '=', 'issues.v_id')
            ->join('journals', 'journals.j_id', '=', 'volume.j_id')
            ->where('journals.j_id', $journal)
            ->orderBy('article.id', 'desc')
            ->take(5)
            ->where('article.status', 1)
            ->select('*', 'article.name as name', 'article.slug as slug', 'article.id as id')
            ->get();

        return response()->json(['journal' => $journalData, 'issues' => $issues, 'volume' => $volume, 'editors' => $editors, 'latest_artricle' => $latest_artricle, 'latest_journal_article' => $latest_journal_article, 'chief' => $chief, 'status' => 200]);
    }


    function postJournal(Request $request)
    {
        // return $request->all();

        if ($request->id) {
            if ($request->file('cover')) {
                $photo = time() . '-' . $request->file('cover')->getClientOriginalName();
                $photo = str_replace(' ', '-', $photo);

                $request->file('cover')->move(public_path('assets/journal'), $photo);

                $update = journal::where('j_id', $request->id)->update([
                    'j_name' => $request->title,
                    'slug' => $request->short,
                    'abbr_title' => $request->abbr,
                    'issn' => $request->issn,
                    'frequency' => $request->frequency,
                    'language' => $request->language,
                    'publisher' => $request->publisher,
                    'country_of_origin' => $request->countryOfOrigin,
                    'aim_and_scope' => $request->aimAndScope,
                    'photo' => $photo
                ]);


            } else {
                $update = journal::where('j_id', $request->id)->update([
                    'j_name' => $request->title,
                    'slug' => $request->short,
                    'abbr_title' => $request->abbr,
                    'issn' => $request->issn,
                    'frequency' => $request->frequency,
                    'language' => $request->language,
                    'publisher' => $request->publisher,
                    'country_of_origin' => $request->countryOfOrigin,
                    'aim_and_scope' => $request->aimAndScope
                ]);


            }
        } else {


            if ($request->file('cover')) {
                $photo = time() . '-' . $request->file('cover')->getClientOriginalName();
                $photo = str_replace(' ', '-', $photo);

                $request->file('cover')->move(public_path('assets/journal'), $photo);
            } else {
                $photo = null;
            }
            $save = new journal;
            $save->j_name = $request->title;
            $save->slug = $request->short;
            $save->abbr_title = $request->abbr;
            $save->issn = $request->issn;
            $save->frequency = $request->frequency;
            $save->language = $request->language;
            $save->publisher = $request->publisher;
            $save->country_of_origin = $request->countryOfOrigin;
            $save->aim_and_scope = $request->aimAndScope;
            $save->photo = $photo;

            DB::transaction(fn () => $save->save());

            return response()->json(['message' => 'Added Successfully', 'status' => 200]);
        }
    }


    function postEditor(Request $request)
    {

        $journalData = journal::where('slug', $request->j_id)->first();

        if ($request->editorId) {

            if ($request->file('cover')) {
                $photo = time() . '-' . $request->file('cover')->getClientOriginalName();
                $photo = str_replace(' ', '-', $photo);
                $request->file('cover')->move(public_path('assets/editors'), $photo);


                $update = editorsData::where('id', $request->editorId)->update([
                    'name' => $request->name,
                    'details' => $request->details,
                    'type' => $request->type,
                    'image' => $photo,
                    'profile' => $request->profile,
                    'interest' => $request->interest,
                    'biography' => $request->biography,
                    'isFeature' => $request->checked,

                ]);
            } else {
                $update = editorsData::where('id', $request->editorId)->update([
                    'name' => $request->name,
                    'details' => $request->details,
                    'type' => $request->type,
                    'profile' => $request->profile,
                    'interest' => $request->interest,
                    'biography' => $request->biography,
                    'isFeature' => $request->checked,

                ]);

            }

            return response()->json(['message' => 'Updated Successfully', 'status' => 200]);

        } else {

            if ($request->file('cover')) {
                $photo = time() . '-' . $request->file('cover')->getClientOriginalName();
                $photo = str_replace(' ', '-', $photo);
                $request->file('cover')->move(public_path('assets/editors'), $photo);
            } else {
                $photo = null;
            }

            $save = new editorsData;
            $save->name = $request->name;
            $save->details = $request->details;
            $save->type = $request->type;
            $save->image = $photo;
            $save->profile = $request->profile;
            $save->j_id = $journalData->j_id;
            $save->interest = $request->interest;
            $save->biography = $request->biography;
            $save->isFeature = $request->checked;

            $save->save();

            return response()->json(['message' => 'Data saved', 'status' => 200]);
        }

    }


    function getEditor($id)
    {
        $editor = editorsData::where('id', $id)->first();

        return response()->json(['editor' => $editor, 'status' => 200]);
    }


    function getVolume($id)
    {
        $journalData = journal::where('j_id', $id)->first();

        $issues = DB::table('volume')
            ->join('issues', 'issues.v_id', '=', 'volume.id')
            ->where('volume.j_id', $id)
            ->select('issues.name as issues', 'issues.id as issue_id', 'volume.name as volume')
            ->get();

        $volume = DB::table('volume')->where('j_id', $id)

            ->orderBy('id', 'DESC')
            ->get();

        $editors = DB::table('editors_data')->where('j_id', $id)
            ->where('active', 1)
            ->orderBy('id', 'DESC')
            ->get();



        return response()->json(['journal' => $journalData, 'issues' => $issues, 'volume' => $volume, 'editors' => $editors, 'status' => 200]);


    }

    function addVolume(Request $request)
    {
        // return $request->volume['name'];

        $message = '';
        // if ($request->volume['id']) {
        if (!empty($request->volume['id'])) {
            $message = 'updated';

            $update = volume::where('id', $request->volume['id'])->update([
                'name' => $request->volume['name'],
                'year' => $request->volume['year'],
            ]);

        } else {

            $save = new volume;
            $save->name = $request->volume['name'];
            $save->year = $request->volume['year'];
            $save->j_id = $request->id;
            $save->save();
            $message = 'created';
        }

        return response()->json(['message' => $message, 'status' => 200]);
    }

    function getIssue($id)
    {
        $issue = issue::where('v_id', $id)->get();

        return response()->json(['issue' => $issue]);
    }

    function saveIssue(Request $request)
    {
        // return $request->all();
        $save = new issue;
        $save->name = $request->issue;
        $save->v_id = $request->id;

        $save->save();

        return response()->json(['message' => 'Successfully Added', 'status' => 200]);
    }


    function updateIssue(Request $request)
    {

        $update = issue::where('id', $request->data['id'])->update([
            'name' => $request->data['name']
        ]);

        return response()->json(['message' => 'Updated successfully !']);
    }


    function getArticle($id)
    {

        $article = article::with('authors')->where('i_id', $id)
            ->orderBy('id', 'DESC')
            ->where('status', 1)
            ->get();

        $getIssue = issue::get()->where('id', $id)->first();
        $getVol = volume::get()->where('id', $getIssue->v_id)->first();

        $getJournal = journal::get()->where('j_id', $getVol->j_id)->first();

        return response()->json(['article' => $article, 'getJournal' => $getJournal, 'getVol' => $getVol, 'getIssue' => $getIssue, 'status' => 200]);
    }




    function postArticle(Request $request)
    {
        $dates = $request->validate([
            'received'=>'sometimes|nullable|date_format:Y-m-d',
            'revised'=>'sometimes|nullable|date_format:Y-m-d',
            'accepted'=>'sometimes|nullable|date_format:Y-m-d',
        ]);
        $authors = null;
        if ($request->has('authors')) {
            $decoded = is_array($request->authors) ? $request->authors : json_decode($request->authors, true);
            $request->merge(['authors'=>$decoded]);
            $validated = $request->validate([
                'authors'=>'present|array|max:100',
                'authors.*.first_name'=>'required|string|max:255',
                'authors.*.last_name'=>'nullable|string|max:255',
                'authors.*.designation'=>'nullable|string|max:2000',
                'authors.*.affiliation'=>'required|string|max:4000',
                'authors.*.corresponding'=>'required|boolean',
                'authors.*.super_no'=>'required|integer|min:1|max:9999',
            ]);
            $authors = $validated['authors'];
            if ($authors) $request->merge(\App\Services\ArticleAuthorFormatter::format($authors));
            else $request->validate(['aname'=>'required|string']);
        }

        return DB::transaction(function () use ($request, $authors, $dates) {
        $file = null;

        if (!$request->id || $request->id == 'undefined') {
            // new add 
            if ($request->file('file')) {
                $file = time() . '-' . $request->file('file')->getClientOriginalName();
                $file = str_replace(' ', '-', $file);
                $request->file('file')->move(public_path('assets/articles'), $file);
            }

            $string = str_replace(' ', '-', $request->name);
            $slug = preg_replace('/[^A-Za-z0-9\-]/', '', $string);


            $save = new article;
            $save->name = $request->name;
            $save->slug = $slug;
            $save->aname = $request->aname;
            $save->abstract = $request->abstract;
            $save->designation = $request->designation;
            $save->doi = $request->doi;
            $save->googleScholar = $request->googleScholar;
            $save->page = $request->page;
            $save->published_date = $request->published_date;
            $save->keywords = $request->keywords;
            $save->file = $file;
            $save->i_id = $request->i_id;

            $save->save();
            $articleId = $save->id;


        } else {
            $articleId = $request->id;
            abort_unless(DB::table('article')->where('id', $articleId)->exists(), 404);

            // return "Update" . $request->id;


            if ($request->file('file')) {
                $file = time() . '-' . $request->file('file')->getClientOriginalName();
                $file = str_replace(' ', '-', $file);
                $request->file('file')->move(public_path('assets/articles'), $file);


                $update = DB::table('article')->where('id', $request->id)->update([
                    'name' => $request->name,
                    'aname' => $request->aname,
                    'abstract' => $request->abstract,
                    'designation' => $request->designation,
                    'doi' => $request->doi,
                    'googleScholar' => $request->googleScholar,
                    'published_date' => $request->published_date,
                    'keywords' => $request->keywords,
                    'page' => $request->page,
                    'file' => $file,
                ]);
            } else {
                $update = DB::table('article')->where('id', $request->id)->update([
                    'name' => $request->name,
                    'aname' => $request->aname,
                    'abstract' => $request->abstract,
                    'designation' => $request->designation,
                    'doi' => $request->doi,
                    'googleScholar' => $request->googleScholar,
                    'published_date' => $request->published_date,
                    'keywords' => $request->keywords,
                    'page' => $request->page,
                ]);
            }
        }

        if ($dates) DB::table('article')->where('id', $articleId)->update($dates);
        if ($authors !== null) {
            DB::table('article_authors')->where('article_id', $articleId)->delete();
            foreach ($authors as $position => $author) {
                \App\Models\ArticleAuthor::create([
                    'article_id'=>$articleId,
                    'first_name'=>$author['first_name'], 'last_name'=>$author['last_name'] ?? null,
                    'designation'=>$author['designation'] ?? null, 'affiliation'=>$author['affiliation'],
                    'corresponding'=>$author['corresponding'], 'super_no'=>$author['super_no'], 'position'=>$position,
                ]);
            }
        }
        return response()->json(['message' => 'Data saved', 'status' => 200]);
        });
    }


    function deleteArticle(Request $request)
    {

        $fileName = DB::table('article')->where('id', $request->id)->first()->file;
        $update = DB::table('article')->where('id', $request->id)->update([
            'status' => 0,
        ]);

        $filePath = public_path('assets/articles/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);

            return response()->json(['message' => 'success', 'data' => $request->id]);
        }
    }



    function postManuscript(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,docx',
        ]);

        if ($request->file('file')) {
            $file = time() . '-' . $request->file('file')->getClientOriginalName();
            $file = str_replace(' ', '-', $file);
            $request->file('file')->move(public_path('assets/manuscript'), $file);
        }
        $save = DB::table('manuscript')->insert([
            'mode' => $request->mode,
            'type' => $request->type,
            'journal' => $request->journal,
            'author' => $request->author,
            'country' => $request->country,
            'affiliation' => $request->affiliation,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'manuscript' => $request->manuscript,
            'file' => $file
        ]);

        return response()->json(['message' => 'Saved', 'status' => 200]);

    }



    function getManuscript()
    {
        $manuscript = DB::table('manuscript')->orderBy('m_id', 'desc')->get();

        return response()->json(['manuscript' => $manuscript, 'status' => 200]);
    }

    function postReviewerData(Request $request)
    {
        // return $request->all();

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'specialization' => 'required',
            'affiliation' => 'required',
            'cv' => 'required|mimes:pdf,docx',
            'photo' => 'required|mimes:jpg,jpeg,png',

        ]);


        if ($request->file('cv')) {
            $cv = time() . '-' . $request->file('cv')->getClientOriginalName();
            $cv = str_replace(' ', '-', $cv);
            $request->file('cv')->move(public_path('assets/reviewer/cv'), $cv);
        }

        if ($request->file('photo')) {
            $photo = time() . '-' . $request->file('photo')->getClientOriginalName();
            $photo = str_replace(' ', '-', $photo);
            $request->file('photo')->move(public_path('assets/reviewer/photo'), $photo);
        }


        $save = DB::table('reviewers')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'specialization' => $request->specialization,
            'affiliation' => $request->affiliation,
            'publication' => $request->publication,
            'cv' => $cv,
            'photo' => $photo,
        ]);

        return response()->json(['message' => 'Saved', 'status' => 200]);
    }

    function postEditorData(Request $request)
    {
        // return $request->all();

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'specialization' => 'required',
            'affiliation' => 'required',
            'cv' => 'required|mimes:pdf,docx',
            'photo' => 'required|mimes:jpg,jpeg,png',

        ]);


        if ($request->file('cv')) {
            $cv = time() . '-' . $request->file('cv')->getClientOriginalName();
            $cv = str_replace(' ', '-', $cv);
            $request->file('cv')->move(public_path('assets/editorsRequest/cv'), $cv);
        }

        if ($request->file('photo')) {
            $photo = time() . '-' . $request->file('photo')->getClientOriginalName();
            $photo = str_replace(' ', '-', $photo);
            $request->file('photo')->move(public_path('assets/editorsRequest/photo'), $photo);
        }


        $save = DB::table('editors')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'specialization' => $request->specialization,
            'affiliation' => $request->affiliation,
            'publication' => $request->publication,
            'cv' => $cv,
            'photo' => $photo,
        ]);

        return response()->json(['message' => 'Saved', 'status' => 200]);
    }
    function postAuthorData(Request $request)
    {
        // return $request->all();

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'specialization' => 'required',
            'affiliation' => 'required',
            'cv' => 'required|mimes:pdf,docx',
            'photo' => 'required|mimes:jpg,jpeg,png',

        ]);


        if ($request->file('cv')) {
            $cv = time() . '-' . $request->file('cv')->getClientOriginalName();
            $cv = str_replace(' ', '-', $cv);
            $request->file('cv')->move(public_path('assets/authors/cv'), $cv);
        }

        if ($request->file('photo')) {
            $photo = time() . '-' . $request->file('photo')->getClientOriginalName();
            $photo = str_replace(' ', '-', $photo);
            $request->file('photo')->move(public_path('assets/authors/photo'), $photo);
        }


        $save = DB::table('authors')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'country' => $request->country,
            'specialization' => $request->specialization,
            'affiliation' => $request->affiliation,
            'publication' => $request->publication,
            'cv' => $cv,
            'photo' => $photo,
        ]);

        return response()->json(['message' => 'Saved', 'status' => 200]);
    }


    function getReviewerRequest()
    {
        $reviewers = DB::table('reviewers')->get();

        return response()->json(['reviewers' => $reviewers, 'status' => 200]);

    }
    function getEditorRequest()
    {
        $reviewers = DB::table('editors')->get();

        return response()->json(['reviewers' => $reviewers, 'status' => 200]);

    }
    function getAuthorRequest()
    {
        $reviewers = DB::table('authors')->get();

        return response()->json(['reviewers' => $reviewers, 'status' => 200]);

    }

    function countSection()
    {
        $tot_journal = DB::table('journals')->where('active', 1)->count();
        $tot_editors = DB::table('editors_data')->where('active', 1)->count();
        $tot_volume = DB::table('volume')->count();
        $tot_issue = DB::table('issues')->count();
        $tot_article = DB::table('article')->where('status', 1)->count();
        $tot_manuscript = DB::table('manuscript')->count();
        $tot_reviewer = DB::table('reviewers')->count();
        $tot_editors_req = DB::table('editors')->count();
        $visitor = DB::table('visitors')->count();
        $tot_dowloads = DB::table('article')->sum('count');




        return response()->json([
            'tot_journal' => $tot_journal,
            'tot_editors' => $tot_editors,
            'tot_volume' => $tot_volume,
            'tot_issue' => $tot_issue,
            'tot_article' => $tot_article,
            'tot_manuscript' => $tot_manuscript,
            'tot_reviewer' => $tot_reviewer,
            'tot_editors_req' => $tot_editors_req,
            'visitor' => $visitor,
            'tot_dowloads' => $tot_dowloads
        ]);
    }

    function journalDetailsForUpdate($slug)
    {
        $journal = DB::table('journals')->where('slug', $slug)
            ->select('*', 'j_name as title', 'slug as short', 'abbr_title as abbr', 'country_of_origin as countryOfOrigin')
            ->first();

        $chief = DB::table('editors_data')->where('j_id', $journal->j_id)->where('type', 'chief')->where('active', 1)->first();

        return response()->json(['chief' => $chief, 'journal' => $journal]);

    }


    function visitor()
    {
        $visitor = DB::table('visitors')->count();

        $save = DB::table('visitors')->insert([
            'count' => 1,
            'ip_address' => request()->ip()
        ]);
        return $visitor;
    }


    function getIndexingById($id)
    {
        $journal = DB::table('journals')->where('slug', $id)
            ->first();

        $indexing = DB::table('indexing')->where('j_id', $journal->j_id)->get();

        return response()->json(['indexing' => $indexing, 'journal' => $journal, 'status' => 200]);
    }


    function getCeertificateById($id)
    {
        $journal = DB::table('journals')->where('slug', $id)
            ->first();

        $certificate = DB::table('certificate')->where('j_id', $journal->j_id)->get();

        return response()->json(['certificate' => $certificate, 'journal' => $journal, 'status' => 200]);
    }

    function postIndexing(Request $request)
    {


        if ($request->id) {
            // return $isShow;
            // return "update";
            if ($request->file('img')) {
                $file = time() . '-' . $request->file('img')->getClientOriginalName();
                $file = str_replace(' ', '-', $file);
                $request->file('img')->move(public_path('assets/indexing'), $file);


                $data = DB::table('indexing')->where('id', $request->id)->update([
                    'link' => $request->link,
                    'img' => $file,
                    'isShow' => $request->isShow
                ]);
            } else {
                $data = DB::table('indexing')->where('id', $request->id)->update([
                    'link' => $request->link,
                    'isShow' => $request->isShow
                ]);
            }

            return response()->json(['message' => 'Indexing Updated Successfully', 'status' => 200]);



        } else {
            // return "create";
            $request->validate([
                'img' => 'required',
            ]);

            if ($request->file('img')) {
                $file = time() . '-' . $request->file('img')->getClientOriginalName();
                $file = str_replace(' ', '-', $file);
                $request->file('img')->move(public_path('assets/indexing'), $file);
            }

            $save = DB::table('indexing')->insert([
                'link' => $request->link,
                'img' => $file,
                'j_id' => $request->j_id,
                'isShow' => $request->isShow
            ]);

            return response()->json(['message' => 'Indexing Added Successfully', 'status' => 200]);
        }





    }
    function postCertificate(Request $request)
    {


        if ($request->id) {
            // return $isShow;
            // return "update";
            if ($request->file('img')) {
                $file = time() . '-' . $request->file('img')->getClientOriginalName();
                $file = str_replace(' ', '-', $file);
                $request->file('img')->move(public_path('assets/certificate'), $file);


                $data = DB::table('certificate')->where('id', $request->id)->update([
                    'title' => $request->title,
                    'img' => $file,
                    'isShow' => $request->isShow
                ]);
            } else {
                $data = DB::table('certificate')->where('id', $request->id)->update([
                    'title' => $request->title,
                    'isShow' => $request->isShow
                ]);
            }

            return response()->json(['message' => 'certificate Updated Successfully', 'status' => 200]);



        } else {
            // return "create";
            $request->validate([
                'img' => 'required',
            ]);

            if ($request->file('img')) {
                $file = time() . '-' . $request->file('img')->getClientOriginalName();
                $file = str_replace(' ', '-', $file);
                $request->file('img')->move(public_path('assets/certificate'), $file);
            }

            $save = DB::table('certificate')->insert([
                'title' => $request->title,
                'img' => $file,
                'j_id' => $request->j_id,
                'isShow' => $request->isShow
            ]);

            return response()->json(['message' => 'certificate Added Successfully', 'status' => 200]);
        }





    }

    function updateArticle(Request $request)
    {
        return $request->all();

    }

    function saveImpactFactor(Request $request)
    {
        $save = DB::table('impact_factors')->insert([
            'j_id' => $request->data['j_id'],
            'impact' => $request->data['impact'],
            'link' => $request->data['link'],
            'isActive' => 1,
        ]);


        return response()->json(['message' => 'Successfully added', 'status' => 200]);
    }

    function getImpactFactor($id)
    {
        $journal = DB::table('journals')->where('slug', $id)
            ->first();
        $impact = DB::table('impact_factors')->where('j_id', $journal->j_id)->where('isActive', 1)->get();

        return response()->json(['impact' => $impact, 'journal' => $journal]);
    }

    function deleteImpactFactor(Request $request)
    {
        $update = DB::table('impact_factors')->where('id', $request->id)->update([
            'isActive' => 0,
        ]);

        return response()->json(['message' => 'Deleted', 'status' => 200]);
    }





}
