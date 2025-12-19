<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('origin_address');
            $table->decimal('origin_lat', 10, 7);
            $table->decimal('origin_lng', 11, 7);
            $table->string('destination_address');
            $table->decimal('destination_lat', 10, 7);
            $table->decimal('destination_lng', 11, 7);
            $table->string('status', 20);
            $table->decimal('price', 10)->nullable();
            $table->timestamps();

            $table->index(['rider_id', 'status']);
            $table->index(['driver_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
