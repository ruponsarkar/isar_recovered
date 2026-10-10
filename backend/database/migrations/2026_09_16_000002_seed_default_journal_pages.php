<?php

use App\Services\JournalPageDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('journals')) return;
        DB::table('journals')->orderBy('j_id')->chunkById(100, function ($journals) {
            foreach ($journals as $journal) app(JournalPageDefaults::class)->seed((int) $journal->j_id);
        }, 'j_id');
    }

    public function down(): void
    {
        // Preserve edited content; rolling back the table migration removes it explicitly.
    }
};
