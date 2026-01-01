<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('driver_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->geography('location_point', 'point', 4326)->index();
            $table->timestamps();
        });

        DB::statement(
            'CREATE INDEX driver_locations_point_gist
             ON driver_locations USING GIST (location_point)',
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};
