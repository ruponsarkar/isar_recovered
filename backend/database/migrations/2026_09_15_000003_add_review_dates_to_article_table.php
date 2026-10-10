<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['received', 'revised', 'accepted'] as $column) {
            if (!Schema::hasColumn('article', $column)) {
                Schema::table('article', fn (Blueprint $table) => $table->date($column)->nullable());
            }
        }
    }

    public function down(): void
    {
        Schema::table('article', fn (Blueprint $table) => $table->dropColumn(['received', 'revised', 'accepted']));
    }
};
