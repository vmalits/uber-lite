<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_documents', static function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('driver_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('type', 30);
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type', 50);
            $table->unsignedInteger('size');
            $table->string('status', 20)->default('pending');
            $table->foreignUlid('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index(['driver_id', 'type']);
            $table->index(['status']);
            $table->index(['expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_documents');
    }
};
