<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SeoRobots extends Command
{
    protected $signature = 'seo:robots';
    protected $description = 'Generate the public robots.txt using the configured production APP_URL';

    public function handle(): int
    {
        File::put(public_path('robots.txt'), app(SitemapController::class)->robots()->getContent());
        $this->info('robots.txt generated. Check APP_URL before deploying.');
        return 0;
    }
}
