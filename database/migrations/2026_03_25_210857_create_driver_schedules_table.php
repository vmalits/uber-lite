<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('driver_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['driver_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_schedules');
    }
};
