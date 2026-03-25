<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Enums\RideStatus;
use Spatie\LaravelData\Data;

final class TrackRideData extends Data
{
    public function __construct(
        public readonly string $ride_id,
        public readonly RideStatus $ride_status,
        public readonly ?string $driver_id,
        public readonly ?DriverRealtimeLocationData $driver_location,
        public readonly ?float $origin_lat,
        public readonly ?float $origin_lng,
        public readonly ?float $destination_lat,
        public readonly ?float $destination_lng,
    ) {}
}
