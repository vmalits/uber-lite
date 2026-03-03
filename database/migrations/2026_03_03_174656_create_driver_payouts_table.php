<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_payouts', function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->foreignUlid('driver_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('amount');
            $table->string('status', 20);
            $table->string('method', 30);

            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_routing_number')->nullable();

            $table->string('crypto_wallet_address')->nullable();
            $table->string('crypto_currency')->nullable();

            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->string('failure_reason')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index(['driver_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_payouts');
    }
};
