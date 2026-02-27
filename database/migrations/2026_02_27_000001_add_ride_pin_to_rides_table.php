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
            $table->string('ride_pin', 4)->nullable();
            $table->timestamp('pin_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table): void {
            $table->dropColumn(['ride_pin', 'pin_verified_at']);
        });
    }
};
