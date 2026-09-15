<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('article_authors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id')->index();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->text('designation')->nullable();
            $table->text('affiliation');
            $table->boolean('corresponding')->default(false);
            $table->unsignedInteger('super_no');
            $table->unsignedInteger('position');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_authors');
    }
};
