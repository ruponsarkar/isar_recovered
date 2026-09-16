<?php

namespace App\Services;

use App\Models\JournalPage;
use App\Support\JournalPageHtml;
use Illuminate\Support\Facades\DB;

class JournalPageDefaults
{
    /** Copy defaults once; subsequent publisher-page edits never change a journal's content. */
    public function seed(int $journalId): void
    {
        DB::transaction(function () use ($journalId) {
            $journal = DB::table('journals')->where('j_id', $journalId)->lockForUpdate()->first();
            if (!$journal || JournalPage::where('journal_id', $journalId)->exists()) return;
            $copy = json_decode(file_get_contents(resource_path('journal-pages/default-content.json')), true, 512, JSON_THROW_ON_ERROR);
            $create = function (string $title, string $slug, string $type = 'content', ?string $content = null, ?int $parent = null, bool $nav = true, bool $tabs = false, ?string $system = null, int $order = 0) use ($journalId, $journal) {
                $base = '/journal/'.rawurlencode($journal->slug).'/pages/';
                foreach (['author-instruction'=>'author-guidelines', 'EthicsPolicies'=>'ethics', 'reviewProcess'=>'review-process', 'EditorGuidlines'=>'editor-guidelines', 'ReviewGuidlines'=>'reviewer-guidelines', 'CopyrightPolicyPage'=>'copyright', 'PublicationFee'=>'publication-fee', 'open-access-policy'=>'open-access', 'RefundPolicy'=>'refund', 'GenerativeAIPolicy'=>'generative-ai'] as $old => $new) {
                    $content = str_replace('href="/'.$old.'"', 'href="'.$base.$new.'"', $content ?? '');
                }
                $page = new JournalPage([
                    'title'=>$title, 'slug'=>$slug, 'type'=>$type, 'content'=>JournalPageHtml::clean($content),
                    'parent_id'=>$parent, 'show_in_nav'=>$nav, 'show_in_tabs'=>$tabs,
                    'system_key'=>$system, 'sort_order'=>$order, 'is_published'=>true,
                ]);
                $page->journal_id = $journalId;
                $page->save();
                return $page->id;
            };
            $create('Current Issue', 'current-issue', 'system', null, null, true, true, 'current_issue', 10);
            $create('About the Journal', 'about', 'content', $journal->aim_and_scope ?? '<p>Journal information will be available soon.</p>', null, false, true, null, 20);
            $create('Archive', 'archive', 'system', null, null, false, true, 'archive', 30);
            $create('Editorial Panel', 'editorial-board', 'system', null, null, false, true, 'editorial_board', 40);
            $authors = $create('For Authors', 'for-authors', 'group', null, null, true, false, null, 50);
            $reviewers = $create('For Reviewers', 'for-reviewers', 'group', null, null, true, false, null, 60);
            $editors = $create('For Editors', 'for-editors', 'group', null, null, true, false, null, 70);
            foreach ([
                ['Author Guidelines','author-guidelines',$authors], ['Open Access Policy','open-access',$authors],
                ['Review Process','review-process',$authors], ['Representatives','representatives',$authors],
                ['Copyright Policy','copyright',$authors], ['Cancellation / Refund Policy','refund',$authors],
                ['Generative AI Policy','generative-ai',$authors], ['Reviewer Guidelines','reviewer-guidelines',$reviewers],
                ['Editor Guidelines','editor-guidelines',$editors],
            ] as $order => [$title, $slug, $parent]) {
                $create($title, $slug, 'content', $copy[$slug], $parent, true, false, null, ($order + 1) * 10);
            }
            foreach ([['Join Author','join-author','joinAuthor',$authors], ['Join Reviewer','join-reviewer','joinReviewer',$reviewers], ['Join Editor','join-editor','joinEditor',$editors]] as [$title,$slug,$path,$parent]) {
                $create($title, $slug, 'content', '<p>Apply to join '.htmlspecialchars($journal->j_name, ENT_QUOTES, 'UTF-8').'.</p><p><a href="/'.$path.'">Complete the application form</a>.</p>', $parent, true, false, null, 100);
            }
            $create('About Us', 'about-us', 'content', str_replace('<h2>Our Journal List</h2>', '', $copy['about']), null, true, false, null, 80);
            // Keep the current fee text but only this journal's row from the publisher's table.
            $fees = preg_replace_callback('~<tr><td>(.*?)</td>(.*?)</tr>~s', fn ($row) => html_entity_decode(strip_tags($row[1]), ENT_QUOTES, 'UTF-8') === $journal->j_name ? $row[0] : '', $copy['publication-fee']);
            if (str_contains($fees, '<tbody></tbody>')) {
                $fees = preg_replace('~<div class="table-responsive">.*?</div>~s', '<p>Contact the editorial office for this journal’s standard publication fee.</p>', $fees);
            }
            $create('Publication Fee', 'publication-fee', 'content', $fees, null, true, false, null, 90);
            $create('Ethics & Policies', 'ethics', 'content', $copy['ethics'], null, true, false, null, 100);
            $create('Payment', 'payment', 'content', '<p>Please review this journal’s <a href="/PublicationFee">publication fee</a> before making a payment.</p><p><a href="/Payment">Continue to secure payment</a>.</p>', null, true, false, null, 110);
            $create('Contact Us', 'contact', 'content', '<p><strong>'.htmlspecialchars($journal->j_name, ENT_QUOTES, 'UTF-8').'</strong></p><p>H.No. 61, Ward No. 4, Bokakhat, Golaghat, Assam, India, 785612</p><p><a href="mailto:contact@isarpublisher.com">contact@isarpublisher.com</a></p><p><a href="tel:+918638994354">+91 8638994354</a></p>', null, true, false, null, 120);
            $create('Submit Manuscript', 'submit-manuscript', 'content', '<p>Submit your manuscript to '.htmlspecialchars($journal->j_name, ENT_QUOTES, 'UTF-8').'. Please read the <a href="/author-instruction">author guidelines</a> before submitting.</p><p><a href="/manuscript">Open the manuscript submission form</a>.</p>', null, true, false, null, 130);
        });
    }
}
