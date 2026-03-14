<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('ride_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['ride_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_messages');
    }
};
