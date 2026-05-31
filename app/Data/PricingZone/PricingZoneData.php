<?php

declare(strict_types=1);

namespace App\Data\PricingZone;

use App\Data\DateData;
use App\Models\PricingZone;
use Spatie\LaravelData\Data;

final class PricingZoneData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public bool $is_enabled,
        public float $surge_multiplier,
        public ?string $reason,
        public float $center_lat,
        public float $center_lng,
        public int $radius_meters,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(PricingZone $zone): self
    {
        return new self(
            id: $zone->id,
            name: $zone->name,
            slug: $zone->slug,
            is_enabled: $zone->is_enabled,
            surge_multiplier: $zone->surge_multiplier,
            reason: $zone->reason,
            center_lat: $zone->center_lat,
            center_lng: $zone->center_lng,
            radius_meters: $zone->radius_meters,
            created_at: DateData::fromCarbon($zone->created_at),
            updated_at: DateData::fromCarbon($zone->updated_at),
        );
    }
}
