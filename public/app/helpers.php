<?php
if (!function_exists('asset_url')) { function asset_url(string $path): string { $base=rtrim((string) env('ASSET_BASE_URL'), '/'); return $base ? $base.'/assets/'.$path : asset('assets/'.$path); } }
