<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Data\DateData;
use App\Enums\DriverAvailabilityStatus;
use App\Models\DriverLocation;
use Spatie\LaravelData\Data;

final class DriverLocationData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $driver_id,
        public readonly DriverAvailabilityStatus $status,
        public readonly float $lat,
        public readonly float $lng,
        public readonly ?DateData $last_active_at,
        public readonly DateData $updated_at,
        public readonly DateData $created_at,
    ) {}

    public static function fromModel(DriverLocation $location): self
    {
        return new self(
            id: $location->id,
            driver_id: $location->driver_id,
            status: $location->status,
            lat: $location->lat,
            lng: $location->lng,
            last_active_at: $location->last_active_at !== null
                ? DateData::fromCarbon($location->last_active_at)
                : null,
            updated_at: DateData::fromCarbon($location->updated_at),
            created_at: DateData::fromCarbon($location->created_at),
        );
    }
}
