<?php

namespace App\Services;

class Seo
{
    public static function url(string $path): string
    {
        return rtrim(config('app.url'), '/').'/'.ltrim($path, '/');
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
