<?php

declare(strict_types=1);

namespace App\Services\Ride;

use App\Dto\Rider\PricingZoneData;
use App\Models\PricingZone;
use Illuminate\Support\Facades\Cache;

final readonly class PricingZonesService
{
    private const int CACHE_TTL_SECONDS = 60;

    /**
     * @return array<int, PricingZoneData>
     */
    public function getActiveZones(): array
    {
        /** @var array<int, PricingZoneData> $result */
        $result = Cache::remember(
            'pricing:zones:active',
            self::CACHE_TTL_SECONDS,
            fn (): array => $this->loadZones(),
        );

        return $result;
    }

    /**
     * @return array<int, PricingZoneData>
     */
    private function loadZones(): array
    {
        return PricingZone::query()
            ->where('is_enabled', true)
            ->get()
            ->map(fn (PricingZone $zone): PricingZoneData => new PricingZoneData(
                id: $zone->id,
                name: $zone->name,
                surge_multiplier: (float) $zone->surge_multiplier,
                is_active: (float) $zone->surge_multiplier > 1.0,
                reason: $zone->reason,
                center: ['lat' => (float) $zone->center_lat, 'lng' => (float) $zone->center_lng],
                radius_meters: $zone->radius_meters,
            ))
            ->all();
    }
}
