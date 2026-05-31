<?php

declare(strict_types=1);

namespace App\Data\PricingZone;

use Spatie\LaravelData\Data;

final class PricingZoneInputData extends Data
{
    public function __construct(
        public string $name,
        public string $slug,
        public bool $is_enabled,
        public float $surge_multiplier,
        public ?string $reason,
        public float $center_lat,
        public float $center_lng,
        public int $radius_meters,
    ) {}
}
