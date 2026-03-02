<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_tips', static function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->foreignUlid('ride_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUlid('rider_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUlid('driver_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('amount');
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique(['ride_id']);
            $table->index(['driver_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_tips');
    }
};
