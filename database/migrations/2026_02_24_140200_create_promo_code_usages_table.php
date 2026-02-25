<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_code_usages', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('promo_code_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('ride_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('discount_applied');
            $table->timestamps();

            $table->unique(['promo_code_id', 'user_id', 'ride_id']);
            $table->index(['user_id', 'promo_code_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_code_usages');
    }
};
