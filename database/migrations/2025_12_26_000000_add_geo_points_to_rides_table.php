<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            // PostGIS geography points (WGS84)
            $table->geography('origin_point', 'point')
                ->nullable()
                ->after('origin_lng');

            $table->geography('destination_point', 'point')
                ->nullable()
                ->after('destination_lng');
        });

        DB::statement('CREATE INDEX rides_origin_point_gist ON rides USING GIST (origin_point)');
        DB::statement('CREATE INDEX rides_destination_point_gist ON rides USING GIST (destination_point)');

        DB::statement(
            'UPDATE rides
             SET origin_point = ST_SetSRID(ST_MakePoint(origin_lng, origin_lat), 4326)::geography
             WHERE origin_lat IS NOT NULL AND origin_lng IS NOT NULL',
        );

        DB::statement(
            'UPDATE rides
             SET destination_point = ST_SetSRID(ST_MakePoint(destination_lng, destination_lat), 4326)::geography
             WHERE destination_lat IS NOT NULL AND destination_lng IS NOT NULL',
        );

        Schema::table('rides', function (Blueprint $table) {
            $table->geography('origin_point', 'point', 4326)
                ->nullable(false)
                ->change();

            $table->geography('destination_point', 'point', 4326)
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS rides_origin_point_gist');
        DB::statement('DROP INDEX IF EXISTS rides_destination_point_gist');

        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn('origin_point');
            $table->dropColumn('destination_point');
        });
    }
};
