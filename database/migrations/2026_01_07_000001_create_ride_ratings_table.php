<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_ratings', static function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->foreignUlid('ride_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUlid('rider_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique(['ride_id', 'rider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_ratings');
    }
};
