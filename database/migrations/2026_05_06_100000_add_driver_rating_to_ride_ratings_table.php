<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ride_ratings', static function (Blueprint $table): void {
            $table->unsignedTinyInteger('driver_rating')->nullable()->after('comment');
            $table->text('driver_comment')->nullable()->after('driver_rating');
        });
    }

    public function down(): void
    {
        Schema::table('ride_ratings', static function (Blueprint $table): void {
            $table->dropColumn(['driver_rating', 'driver_comment']);
        });
    }
};
