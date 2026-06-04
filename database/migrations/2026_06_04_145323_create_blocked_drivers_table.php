<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_drivers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rider_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignUlid('driver_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['rider_id', 'driver_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_drivers');
    }
};
