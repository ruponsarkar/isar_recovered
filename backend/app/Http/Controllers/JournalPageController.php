<?php

namespace App\Http\Controllers;

use App\Models\JournalPage;
use App\Support\JournalPageHtml;
use App\Support\JournalPageLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JournalPageController extends Controller
{
    private function journal(string $slug)
    {
        $journal = DB::table('journals')->where('slug', $slug)->first();
        abort_unless($journal, 404);
        return $journal;
    }

    public function index(string $journal)
    {
        $record = $this->journal($journal);
        return response()->json(['journal'=>$record, 'pages'=>JournalPage::where('journal_id', $record->j_id)->orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function published(string $journal)
    {
        $record = $this->journal($journal);
        $pages = JournalPage::where('journal_id', $record->j_id)->where('is_published', true)->orderBy('sort_order')->orderBy('id')->get();
        // A draft dropdown hides its children, including direct page URLs.
        $groups = $pages->where('type', 'group')->pluck('id')->all();
        $pages = $pages->filter(fn ($page) => !$page->parent_id || in_array($page->parent_id, $groups, true))->values();
        return response()->json(['pages'=>$pages]);
    }

    public function show(string $journal, string $page)
    {
        $pages = $this->published($journal)->getData(true)['pages'];
        $record = collect($pages)->first(fn ($row) => $row['slug'] === $page && $row['type'] !== 'group');
        abort_unless($record, 404);
        return response()->json(['page'=>$record]);
    }

    public function store(Request $request, string $journal)
    {
        return $this->save($request, $journal);
    }

    public function update(Request $request, string $journal, int $page)
    {
        return $this->save($request, $journal, $page);
    }

    private function save(Request $request, string $journal, ?int $id = null)
    {
        $record = $this->journal($journal);
        return DB::transaction(function () use ($request, $record, $id) {
            // Serialize menu changes so parent edits cannot race against child creation.
            DB::table('journals')->where('j_id', $record->j_id)->lockForUpdate()->first();
            $page = $id === null ? new JournalPage : JournalPage::where('journal_id', $record->j_id)->findOrFail($id);
            $data = $request->validate([
                'title'=>['required','string','max:150'],
                'slug'=>['required','string','max:100','regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', Rule::unique('journal_pages')->where('journal_id', $record->j_id)->ignore($id)],
                'type'=>['required', Rule::in(['content','group','system','link'])],
                'link_url'=>['bail', 'nullable', Rule::requiredIf($request->input('type') === 'link'), 'string', 'max:2048', function ($attribute, $value, $fail) {
                    if (!JournalPageLink::valid($value)) $fail('Enter a complete http:// or https:// URL, or an internal path starting with one / (for example /Payment).');
                }],
                'system_key'=>['nullable', Rule::requiredIf($request->input('type') === 'system'), Rule::in(['current_issue','archive','editorial_board'])],
                'content'=>['nullable','string','max:2000000'],
                'parent_id'=>['nullable','integer', Rule::exists('journal_pages','id')->where('journal_id', $record->j_id)->where('type','group')->whereNull('parent_id')],
                'sort_order'=>['required','integer','min:0','max:100000'],
                'is_published'=>['required','boolean'], 'show_in_nav'=>['required','boolean'], 'show_in_tabs'=>['required','boolean'],
            ]);
            if ($data['type'] === 'group' && (!empty($data['parent_id']) || $data['show_in_tabs'])) {
                throw ValidationException::withMessages(['type'=>'Dropdown groups must be at the top level and cannot appear as journal tabs.']);
            }
            if ($id && (int) ($data['parent_id'] ?? 0) === $id) {
                throw ValidationException::withMessages(['parent_id'=>'A page cannot be its own parent.']);
            }
            if ($id && $data['type'] !== 'group' && JournalPage::where('parent_id', $id)->exists()) {
                throw ValidationException::withMessages(['type'=>'Move or delete the pages in this dropdown before changing its type.']);
            }
            $data['parent_id'] = $data['parent_id'] ?? null;
            $data['system_key'] = $data['type'] === 'system' ? $data['system_key'] : null;
            $data['content'] = $data['type'] === 'content' ? JournalPageHtml::clean($data['content'] ?? '') : null;
            $data['link_url'] = $data['type'] === 'link' ? $data['link_url'] : null;
            $page->fill($data);
            $page->journal_id = $record->j_id;
            $page->save();
            return response()->json(['page'=>$page->fresh()], $id === null ? 201 : 200);
        });
    }

    public function destroy(string $journal, int $page)
    {
        $record = $this->journal($journal);
        return DB::transaction(function () use ($record, $page) {
            DB::table('journals')->where('j_id', $record->j_id)->lockForUpdate()->first();
            $entry = JournalPage::where('journal_id', $record->j_id)->findOrFail($page);
            if (JournalPage::where('parent_id', $entry->id)->exists()) {
                throw ValidationException::withMessages(['page'=>'Move or delete the pages in this dropdown before deleting it.']);
            }
            $entry->delete();
            return response()->noContent();
        });
    }
}
