<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_stops', static function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->foreignUlid('ride_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('order');
            $table->string('address');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->geography('point', 'Point', 4326)->nullable();

            $table->timestamps();

            $table->unique(['ride_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_stops');
    }
};
