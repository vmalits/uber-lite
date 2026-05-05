<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\RideStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class RideTimelineData extends Data
{
    public function __construct(
        public readonly string $ride_id,
        public readonly RideStatus $current_status,
        /** @var DataCollection<int, RideTimelineEventData> */
        public readonly DataCollection $events,
    ) {}
}
