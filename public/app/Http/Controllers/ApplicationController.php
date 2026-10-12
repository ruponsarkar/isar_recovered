<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    private function activeJournals()
    {
        return collect(app(\App\Services\PublicSidebarData::class)->get()['journals'])
            ->filter(fn($journal) => (string) ($journal['active'] ?? '') === '1')
            ->sortBy('sl_no')->values();
    }

    public function form(string $type, Request $request)
    {
        abort_unless(in_array($type, ['manuscript','author','reviewer','editor']), 404);
        $journals = $type === 'manuscript' ? $this->activeJournals() : collect();
        $selectedJournal = $journals->first(fn($journal) => (string) (($journal['slug'] ?? '') ?: $journal['j_id']) === (string) $request->query('journal', ''));
        $selectedJournal = $selectedJournal ? (($selectedJournal['slug'] ?? '') ?: (string) $selectedJournal['j_id']) : '';
        return view('pages.form', compact('type', 'journals', 'selectedJournal'));
    }

    public function submit(string $type, Request $request)
    {
        abort_unless(in_array($type, ['manuscript','author','reviewer','editor']), 404);
        $rules = ['name'=>'required|string|max:150','email'=>'required|email|max:150','country'=>'required|string|max:100','affiliation'=>'required|string|max:200','phone'=>'required|string|max:40','file'=>'nullable|file|mimes:pdf,docx|max:10240'];
        if ($type === 'manuscript') {
            $journals = $this->activeJournals();
            $rules['file'] = 'required|file|mimes:pdf,docx|max:10240';
            $rules += ['mode'=>'required|string','article_type'=>'required|string','journal'=>['required', 'string', \Illuminate\Validation\Rule::in($journals->map(fn($journal) => ($journal['slug'] ?? '') ?: (string) $journal['j_id'])->all())],'title'=>'required|string|max:255'];
        }
        if ($type !== 'manuscript') {
            $rules['file'] = 'required|file|mimes:pdf,docx|max:10240';
            $rules += ['expertise'=>'required|string|max:1000','publication'=>'nullable|string|max:2000','photo'=>'required|image|mimes:jpg,jpeg,png|max:4096'];
        }
        $data = $request->validate($rules);
        if ($type === 'manuscript') {
            $selectedJournal = $journals->first(fn($journal) => (string) (($journal['slug'] ?? '') ?: $journal['j_id']) === $data['journal']);
            $data['journal'] = $selectedJournal['j_name'];
        }
        $savedFiles = [];
        $assetFolders = ['manuscript'=>'manuscript', 'author'=>'authors', 'reviewer'=>'reviewer', 'editor'=>'editorsRequest'];
        try {
        foreach (['file','photo'] as $field) {
            if (!$request->hasFile($field)) continue;
            $upload = $request->file($field);
                $subfolder = $type === 'manuscript' ? '' : '/'.($field === 'file' ? 'cv' : 'photo');
                $directory = rtrim(config('app.backend_asset_path'), '/').'/'.$assetFolders[$type].$subfolder;
                File::ensureDirectoryExists($directory);
                $originalName = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::uuid().'-'.(Str::slug($originalName) ?: $field).'.'.$upload->extension();
                $upload->move($directory, $filename);
                $savedFiles[] = $directory.'/'.$filename;
                $data[$field] = $filename;
        }
            if ($type === 'manuscript') {
                $table = 'manuscript';
                $record = [
                    'mode'=>$data['mode'], 'type'=>$data['article_type'],
                    'journal'=>$data['journal'], 'author'=>$data['name'],
                    'country'=>$data['country'], 'affiliation'=>$data['affiliation'],
                    'email'=>$data['email'], 'mobile'=>$data['phone'],
                    'manuscript'=>$data['title'], 'file'=>$data['file'],
                ];
            } else {
                $table = ['author'=>'authors', 'reviewer'=>'reviewers', 'editor'=>'editors'][$type];
                $record = [
                    'name'=>$data['name'], 'email'=>$data['email'], 'mobile'=>$data['phone'],
                    'country'=>$data['country'], 'specialization'=>$data['expertise'],
                    'affiliation'=>$data['affiliation'], 'publication'=>$data['publication'] ?? '',
                    'cv'=>$data['file'], 'photo'=>$data['photo'],
                ];
            }
            DB::transaction(fn () => DB::table($table)->insert($record));
        } catch (\Throwable $e) {
            foreach ($savedFiles as $path) File::delete($path);
            return back()->withInput()->withErrors(['submission'=>'Your submission could not be saved. Please check the database connection and try again. Please select your upload files again.']);
        }
        return redirect()->route($type === 'manuscript' ? 'manuscript' : "join.{$type}")->with('success', 'Thank you. Your application has been received; the editorial team will contact you shortly.');
    }
}
