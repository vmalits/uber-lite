<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class NearbyDriversResponseData extends Data
{
    /**
     * @param Collection<int, NearbyDriverData> $drivers
     */
    public function __construct(
        public readonly Collection $drivers,
        public readonly int $total_count,
        public readonly NearbyDriversSearchCenterData $search_center,
        public readonly int $radius_meters,
        public readonly ?int $average_eta_seconds,
    ) {}

    /**
     * @param Collection<int, NearbyDriverData> $drivers
     */
    public static function fromDrivers(
        Collection $drivers,
        float $searchLat,
        float $searchLng,
        int $radiusMeters,
    ): self {
        return new self(
            drivers: $drivers,
            total_count: $drivers->count(),
            search_center: new NearbyDriversSearchCenterData(
                lat: $searchLat,
                lng: $searchLng,
            ),
            radius_meters: $radiusMeters,
            average_eta_seconds: $drivers->isNotEmpty()
                ? (int) round($drivers->avg('estimated_arrival_seconds') ?? 0)
                : null,
        );
    }
}
