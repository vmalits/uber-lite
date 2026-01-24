<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Data\Driver\DriverRealtimeLocationData;

final readonly class DriverLocationUpdateResult
{
    public function __construct(
        public DriverRealtimeLocationData $snapshot,
        public bool $shouldPublish,
    ) {}
}
