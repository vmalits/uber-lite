<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            // Estimation
            $table->decimal('estimated_price', 10, 2)->nullable()->after('status');
            $table->decimal('estimated_distance_km', 8, 2)->nullable();
            $table->decimal('estimated_duration_min', 8, 2)->nullable();

            // Pricing snapshot
            $table->decimal('base_fee', 8, 2)->nullable();
            $table->decimal('price_per_km', 8, 2)->nullable();
            $table->decimal('price_per_minute', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_price',
                'estimated_distance_km',
                'estimated_duration_min',
                'base_fee',
                'price_per_km',
                'price_per_minute',
            ]);
        });
    }
};
