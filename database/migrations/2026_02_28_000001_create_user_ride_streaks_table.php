<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_ride_streaks', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('current_streak')->default(0);
            $table->unsignedSmallInteger('longest_streak')->default(0);
            $table->date('last_ride_date')->nullable();
            $table->timestamp('streak_started_at')->nullable();
            $table->timestamps();

            $table->unique('user_id');
            $table->index('current_streak');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_ride_streaks');
    }
};
