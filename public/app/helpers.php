<?php
if (!function_exists('author_text')) {
    function author_text(?string $value): \Illuminate\Support\HtmlString {
        $escaped = e(html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        return new \Illuminate\Support\HtmlString(preg_replace('/&lt;sup&gt;([0-9*\-]+)&lt;\/sup&gt;/', '<sup>$1</sup>', $escaped));
    }
}
if (!function_exists('backend_asset_url')) {
    function backend_asset_url(string $path): string {
        $base = rtrim((string) config('app.backend_asset_url'), '/');
        $path = ltrim($path, '/');

        return $base !== '' ? $base.'/'.$path : asset('assets/'.$path);
    }
}
