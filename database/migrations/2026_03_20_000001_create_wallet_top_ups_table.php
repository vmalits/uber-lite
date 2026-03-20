<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_top_ups', function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->foreignUlid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUlid('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->nullOnDelete();

            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('MDL');
            $table->string('payment_intent_id')->nullable();
            $table->text('failure_reason')->nullable();

            $table->timestamps();
            $table->timestamp('completed_at')->nullable();

            $table->index(['user_id', 'status']);
            $table->index('payment_intent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_top_ups');
    }
};
