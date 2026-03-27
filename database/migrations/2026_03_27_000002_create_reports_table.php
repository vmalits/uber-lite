<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('reporter_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUlid('target_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignUlid('ride_id')
                ->nullable()
                ->constrained('rides')
                ->nullOnDelete();
            $table->string('reason', 30);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignUlid('resolved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['reporter_id', 'target_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
