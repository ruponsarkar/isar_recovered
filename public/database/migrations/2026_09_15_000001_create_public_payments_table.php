<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('public_payments')) {
            Schema::create('public_payments', function (Blueprint $table) {
                $table->id();
                $table->string('provider', 24)->index();
                $table->string('provider_order_id')->nullable()->unique();
                $table->string('provider_payment_id')->nullable()->unique();
                $table->unsignedBigInteger('amount_minor');
                $table->string('currency', 3);
                $table->unsignedBigInteger('base_minor')->nullable();
                $table->unsignedBigInteger('tax_minor')->nullable();
                $table->unsignedBigInteger('total_minor');
                $table->string('payer_name', 150)->nullable();
                $table->string('payer_email')->nullable();
                $table->string('payer_contact', 32)->nullable();
                $table->uuid('reference')->unique();
                $table->string('receipt')->nullable()->index();
                $table->string('status', 24)->default('pending')->index();
                $table->json('gateway_payload')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
            return;
        }

        $columns = [
            'provider' => fn (Blueprint $t) => $t->string('provider', 24)->nullable()->index(),
            'provider_order_id' => fn (Blueprint $t) => $t->string('provider_order_id')->nullable()->unique(),
            'provider_payment_id' => fn (Blueprint $t) => $t->string('provider_payment_id')->nullable()->unique(),
            'amount_minor' => fn (Blueprint $t) => $t->unsignedBigInteger('amount_minor')->nullable(),
            'currency' => fn (Blueprint $t) => $t->string('currency', 3)->nullable(),
            'base_minor' => fn (Blueprint $t) => $t->unsignedBigInteger('base_minor')->nullable(),
            'tax_minor' => fn (Blueprint $t) => $t->unsignedBigInteger('tax_minor')->nullable(),
            'total_minor' => fn (Blueprint $t) => $t->unsignedBigInteger('total_minor')->nullable(),
            'payer_name' => fn (Blueprint $t) => $t->string('payer_name', 150)->nullable(),
            'payer_email' => fn (Blueprint $t) => $t->string('payer_email')->nullable(),
            'payer_contact' => fn (Blueprint $t) => $t->string('payer_contact', 32)->nullable(),
            'reference' => fn (Blueprint $t) => $t->uuid('reference')->nullable()->unique(),
            'receipt' => fn (Blueprint $t) => $t->string('receipt')->nullable()->index(),
            'status' => fn (Blueprint $t) => $t->string('status', 24)->default('pending')->index(),
            'gateway_payload' => fn (Blueprint $t) => $t->json('gateway_payload')->nullable(),
            'paid_at' => fn (Blueprint $t) => $t->timestamp('paid_at')->nullable(),
        ];
        foreach ($columns as $name => $add) {
            if (!Schema::hasColumn('public_payments', $name)) {
                Schema::table('public_payments', $add);
            }
        }
        if (!Schema::hasColumn('public_payments', 'created_at')) {
            Schema::table('public_payments', fn (Blueprint $t) => $t->timestamps());
        }
    }

    public function down(): void
    {
        // Preserve public-site transaction records on rollback.
    }
};
