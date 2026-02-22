<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\HeatmapPointData;
use App\Enums\RideStatus;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetHeatmapQuery implements GetHeatmapQueryInterface
{
    public function execute(): Collection
    {
        $results = DB::table('rides')
            ->selectRaw('
                ROUND(origin_lat, 3) as lat,
                ROUND(origin_lng, 3) as lng,
                COUNT(*) as intensity
            ')
            ->whereIn('status', [
                RideStatus::PENDING->value,
                RideStatus::SCHEDULED->value,
            ])
            ->where(function (Builder $query) {
                $query->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now()->addMinutes(30));
            })
            ->groupBy('lat', 'lng')
            ->get();

        $maxIntensity = $results->max('intensity');
        $maxIntensityValue = is_numeric($maxIntensity) ? (int) $maxIntensity : 1;
        if ($maxIntensityValue === 0) {
            $maxIntensityValue = 1;
        }

        return $results->map(fn (object $row) => new HeatmapPointData(
            lat: is_numeric($row->lat ?? null) ? (float) $row->lat : 0.0,
            lng: is_numeric($row->lng ?? null) ? (float) $row->lng : 0.0,
            intensity: is_numeric($row->intensity ?? null) ? (int) $row->intensity : 0,
            weight: round(
                (is_numeric($row->intensity ?? null)
                    ? (int) $row->intensity : 0)
                / $maxIntensityValue, 2,
            ),
        ));
    }
}
