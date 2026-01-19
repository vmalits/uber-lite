<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('driver_id')->constrained('users')->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('color');
            $table->string('plate_number')->unique();
            $table->string('vehicle_type');
            $table->unsignedTinyInteger('seats');
            $table->timestamps();

            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
