<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_zones', static function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_enabled')->default(true);
            $table->decimal('surge_multiplier', 4, 2)->default(1.00);
            $table->string('reason', 50)->nullable();
            $table->decimal('center_lat', 10, 7);
            $table->decimal('center_lng', 10, 7);
            $table->unsignedInteger('radius_meters')->default(1000);

            $table->timestamps();

            $table->index(['is_enabled']);
            $table->index(['surge_multiplier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_zones');
    }
};
