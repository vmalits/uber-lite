<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_splits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('ride_id')->constrained()->onDelete('cascade');
            $table->string('participant_name');
            $table->string('participant_email')->nullable();
            $table->string('participant_phone')->nullable();
            $table->decimal('share', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_splits');
    }
};
