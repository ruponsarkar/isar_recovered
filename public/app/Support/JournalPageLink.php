<?php

namespace App\Support;

class JournalPageLink
{
    public static function valid(?string $url): bool
    {
        if (!$url || strlen($url) > 2048 || preg_match('/[\s\\\\]/u', $url)) return false;
        $decoded = rawurldecode($url);
        if (preg_match('/[\x00-\x1f\x7f\\\\]/', $decoded)) return false;
        if (str_starts_with($url, '/')) return !str_starts_with($decoded, '//');
        return preg_match('~^https?://~i', $url) && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
