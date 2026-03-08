<?php

declare(strict_types=1);

namespace App\Dto\Rider;

use Spatie\LaravelData\Data;

/**
 * @property array{lat: float, lng: float}|null $center
 */
final class PricingZoneData extends Data
{
    /**
     * @param array{lat: float, lng: float}|null $center
     */
    public function __construct(
        public string $id,
        public string $name,
        public float $surge_multiplier,
        public bool $is_active,
        public ?string $reason = null,
        public ?array $center = null,
        public int $radius_meters = 0,
    ) {}
}
