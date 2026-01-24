<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Enums\DriverAvailabilityStatus;
use Spatie\LaravelData\Data;

final class DriverRealtimeLocationData extends Data
{
    public function __construct(
        public readonly string $driver_id,
        public readonly DriverAvailabilityStatus $status,
        public readonly ?float $lat,
        public readonly ?float $lng,
        public readonly int $ts,
    ) {}
}
