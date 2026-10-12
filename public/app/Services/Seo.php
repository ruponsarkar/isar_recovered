<?php

namespace App\Services;

class Seo
{
    public static function url(string $path): string
    {
        return rtrim(config('app.url'), '/').'/'.ltrim($path, '/');
    }

    public static function pdfPath($article): string
    {
        $filename = (substr(\Illuminate\Support\Str::slug(strip_tags((string) data_get($article, 'name'))), 0, 150) ?: 'article').'.pdf';
        $slug = data_get($article, 'slug');
        return $slug ? '/pdf/'.rawurlencode($slug).'/'.$filename
            : '/articles/'.data_get($article, 'id').'/'.$filename;
    }

    public static function doi(?string $value): string
    {
        $value = preg_replace('~^(?:https?://(?:dx\.)?doi\.org/|doi:\s*)~i', '', trim($value ?? ''));
        return preg_match('~^10\.\d{4,9}/\S+$~', $value) ? $value : '';
    }

    public static function text(?string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'))));
    }

    public static function authorName(?string $value): string
    {
        return trim(str_replace('*', '', self::text(preg_replace('/<sup\b[^>]*>.*?<\/sup>/is', '', html_entity_decode($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')))));
    }
}
