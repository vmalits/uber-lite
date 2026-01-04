<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_bans', static function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('driver_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUlid('banned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('ban_source', 20);
            $table->string('ban_type', 20);
            $table->text('reason');
            $table->string('external_id', 255)->nullable();

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('unbanned_at')->nullable();
            $table->foreignUlid('unbanned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->text('unban_reason')->nullable();

            $table->timestamps();

            $table->index(['driver_id', 'unbanned_at']);
            $table->index(['ban_type', 'expires_at']);
            $table->index(['ban_source']);
            $table->index(['external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_bans');
    }
};
