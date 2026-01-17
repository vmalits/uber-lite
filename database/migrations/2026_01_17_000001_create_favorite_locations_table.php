<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_locations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('address');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_locations');
    }
};
