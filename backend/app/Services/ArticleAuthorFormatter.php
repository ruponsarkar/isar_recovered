<?php

namespace App\Services;

class ArticleAuthorFormatter
{
    public static function render(?string $value): \Illuminate\Support\HtmlString
    {
        $escaped = e(html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        return new \Illuminate\Support\HtmlString(preg_replace('/&lt;sup&gt;([0-9*\-]+)&lt;\/sup&gt;/', '<sup>$1</sup>', $escaped));
    }

    public static function format(array $authors): array
    {
        $names = [];
        $groups = [];
        foreach ($authors as $author) {
            $name = trim($author['first_name'].' '.($author['last_name'] ?? ''));
            $star = !empty($author['corresponding']) ? '*' : '';
            $names[] = e($name).$star.'<sup>'.(int) $author['super_no'].'</sup>';
            $affiliation = trim($author['affiliation']);
            $key = mb_strtolower(preg_replace('/\s+/u', ' ', $affiliation));
            $groups[$key]['affiliation'] = $groups[$key]['affiliation'] ?? $affiliation;
            $groups[$key]['numbers'][] = (int) $author['super_no'].$star;
        }
        $affiliations = [];
        foreach ($groups as $group) {
            $affiliations[] = '<sup>'.implode('-', array_unique($group['numbers'])).'</sup>'.e($group['affiliation']);
        }
        return ['aname'=>implode(', ', $names), 'designation'=>implode(', ', $affiliations)];
    }
}
