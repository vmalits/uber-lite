<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('referral_code', 12)->unique()->nullable();
            $table->unsignedInteger('credits_balance')->default(0);
            $table->foreignUlid('referred_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('referred_at')->nullable();

            $table->index(['referral_code']);
            $table->index(['referred_by']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'referral_code',
                'credits_balance',
                'referred_by',
                'referred_at',
            ]);
        });
    }
};
