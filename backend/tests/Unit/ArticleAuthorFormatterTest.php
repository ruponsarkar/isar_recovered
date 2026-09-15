<?php

use App\Services\ArticleAuthorFormatter;
use PHPUnit\Framework\TestCase;

class ArticleAuthorFormatterTest extends TestCase
{
    public function test_names_and_shared_affiliations(): void
    {
        $authors = [];
        foreach ([['P.', 'Kanakasabai', 'College', false, 1], ['Saikat', 'Banerjee', 'College', false, 2],
            ['D.', 'Sridevi', 'IT Department', false, 3], ['S.', 'Sivamani', 'College', true, 4]] as [$first, $last, $affiliation, $corresponding, $number]) {
            $authors[] = ['first_name'=>$first, 'last_name'=>$last, 'affiliation'=>$affiliation,
                'designation'=>'Professor '.$number, 'corresponding'=>$corresponding, 'super_no'=>$number];
        }
        $result = ArticleAuthorFormatter::format($authors);
        $this->assertSame('P. Kanakasabai<sup>1</sup>, Saikat Banerjee<sup>2</sup>, D. Sridevi<sup>3</sup>, S. Sivamani*<sup>4</sup>', $result['aname']);
        $this->assertSame('<sup>1-2-4*</sup>College, <sup>3</sup>IT Department', $result['designation']);
    }

    public function test_user_text_is_escaped(): void
    {
        $result = ArticleAuthorFormatter::format([['first_name'=>'<script>alert(1)</script>', 'last_name'=>'',
            'designation'=>'Professor', 'affiliation'=>'Research & Teaching', 'corresponding'=>false, 'super_no'=>1]]);
        $this->assertStringNotContainsString('<script>', $result['aname']);
        $this->assertSame('<sup>1</sup>Research &amp; Teaching', $result['designation']);
    }

    public function test_only_numeric_superscripts_are_rendered_as_html(): void
    {
        $this->assertSame('Author<sup>1</sup>', (string) ArticleAuthorFormatter::render('Author<sup>1</sup>'));
        $this->assertSame('Author<sup>2</sup>', (string) ArticleAuthorFormatter::render('Author&lt;sup&gt;2&lt;/sup&gt;'));
        $this->assertSame('&lt;script&gt;alert(1)&lt;/script&gt;', (string) ArticleAuthorFormatter::render('<script>alert(1)</script>'));
        $this->assertStringNotContainsString('<sup onclick', (string) ArticleAuthorFormatter::render('<sup onclick="alert(1)">1</sup>'));
    }
}
