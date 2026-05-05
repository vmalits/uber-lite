<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ride_splits', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('ride_id');
            $table->timestamp('responded_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ride_splits', function (Blueprint $table) {
            $table->dropColumn(['status', 'responded_at']);
        });
    }
};
