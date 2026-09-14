<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function form(string $type) { abort_unless(in_array($type, ['manuscript','author','reviewer','editor']), 404); return view('pages.form', compact('type')); }

    public function submit(string $type, Request $request)
    {
        abort_unless(in_array($type, ['manuscript','author','reviewer','editor']), 404);
        $rules = ['name'=>'required|string|max:150','email'=>'required|email|max:150','country'=>'required|string|max:100','affiliation'=>'required|string|max:200','phone'=>'required|string|max:40','file'=>'nullable|file|mimes:pdf,docx|max:10240'];
        if ($type === 'manuscript') $rules += ['mode'=>'required|string','article_type'=>'required|string','journal'=>'required|string|max:200','title'=>'required|string|max:255'];
        if ($type !== 'manuscript') $rules += ['expertise'=>'required|string|max:1000','photo'=>'nullable|image|mimes:jpg,jpeg,png|max:4096'];
        $data = $request->validate($rules);
        foreach (['file','photo'] as $field) if ($request->hasFile($field)) $data[$field] = $request->file($field)->storeAs("uploads/{$type}", Str::uuid().'.'.$request->file($field)->extension(), 'public');
        $base = rtrim((string) env('PUBLIC_API_BASE_URL'), '/');
        if ($base) {
            try { Http::timeout(10)->post("{$base}/post".ucfirst($type), $data)->throw(); } catch (\Throwable $e) { return back()->withInput()->withErrors(['submission'=>'The legacy service could not accept this submission. Please try again shortly.']); }
        }
        return redirect()->route($type === 'manuscript' ? 'manuscript' : "join.{$type}")->with('success', 'Thank you. Your application has been received; the editorial team will contact you shortly.');
    }
}
