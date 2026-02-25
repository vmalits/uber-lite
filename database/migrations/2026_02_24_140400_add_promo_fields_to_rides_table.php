<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table): void {
            $table->unsignedInteger('discount_amount')->nullable();
            $table->foreignUlid('promo_code_id')
                ->nullable()
                ->constrained('promo_codes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table): void {
            $table->dropColumn(['discount_amount', 'promo_code_id']);
        });
    }
};
