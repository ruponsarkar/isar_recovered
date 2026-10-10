<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_details', function (Blueprint $table) {
            // Zero is the publisher default; positive IDs identify journals.
            $table->unsignedBigInteger('journal_id')->primary();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 80)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('contact_details'); }
};
