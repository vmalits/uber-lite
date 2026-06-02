<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class NearbyDriversSearchCenterData extends Data
{
    public function __construct(
        public readonly float $lat,
        public readonly float $lng,
    ) {}
}
