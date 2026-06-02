<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\VehicleType;
use Spatie\LaravelData\Data;

final class NearbyDriversRequestData extends Data
{
    public const int DEFAULT_RADIUS_METERS = 3000;

    public function __construct(
        public readonly float $lat,
        public readonly float $lng,
        public readonly int $radiusMeters = self::DEFAULT_RADIUS_METERS,
        public readonly ?VehicleType $vehicleType = null,
    ) {}
}
