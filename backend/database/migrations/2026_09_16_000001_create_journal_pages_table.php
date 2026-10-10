<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journal_pages', function (Blueprint $table) {
            $table->id();
            // Legacy installations use different integer types for journals.j_id.
            $table->unsignedBigInteger('journal_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title', 150);
            $table->string('slug', 100);
            $table->string('type', 20)->default('content');
            $table->string('system_key', 30)->nullable();
            $table->longText('content')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('show_in_nav')->default(true);
            $table->boolean('show_in_tabs')->default(false);
            $table->timestamps();
            $table->unique(['journal_id', 'slug']);
            $table->foreign('parent_id')->references('id')->on('journal_pages')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_pages');
    }
};
