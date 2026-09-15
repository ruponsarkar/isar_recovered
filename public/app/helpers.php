<?php
if (!function_exists('author_text')) {
    function author_text(?string $value): \Illuminate\Support\HtmlString {
        $escaped = e(html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        return new \Illuminate\Support\HtmlString(preg_replace('/&lt;sup&gt;([0-9*\-]+)&lt;\/sup&gt;/', '<sup>$1</sup>', $escaped));
    }
}
if (!function_exists('asset_url')) { function asset_url(string $path): string { $base=rtrim((string) env('ASSET_BASE_URL'), '/'); return $base ? $base.'/assets/'.$path : asset('assets/'.$path); } }
