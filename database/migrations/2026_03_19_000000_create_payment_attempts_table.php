<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_attempts', function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->foreignUlid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUlid('ride_id')
                ->constrained('rides')
                ->cascadeOnDelete();

            $table->foreignUlid('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->nullOnDelete();

            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('credits_used')->default(0);
            $table->unsignedInteger('card_amount')->default(0);
            $table->string('currency', 3)->default('MDL');
            $table->string('provider', 50)->nullable();
            $table->string('provider_transaction_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->timestamp('completed_at')->nullable();

            $table->index(['user_id', 'status']);
            $table->index(['ride_id', 'status']);
            $table->index('provider_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};
