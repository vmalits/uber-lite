<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->unsignedInteger('price')->nullable()->change();
            $table->unsignedInteger('estimated_price')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->decimal('price', 10)->nullable()->change();
            $table->decimal('estimated_price', 10, 2)->nullable()->change();
        });
    }
};
