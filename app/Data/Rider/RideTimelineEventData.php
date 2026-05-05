<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\RideStatus;
use Spatie\LaravelData\Data;

final class RideTimelineEventData extends Data
{
    public function __construct(
        public readonly RideStatus $status,
        public readonly ?string $timestamp,
    ) {}
}
