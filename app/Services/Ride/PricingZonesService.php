<?php

declare(strict_types=1);

namespace App\Services\Ride;

use App\Dto\Rider\PricingZoneData;
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
        /** @var array<int, array{id: string, name: string, enabled: bool, surge_multiplier: float, reason: string|null, center: array{lat: float, lng: float}, radius_meters: int}> $zones */
        $zones = config('pricing.zones', []);
        $activeZones = [];

        foreach ($zones as $zone) {
            if ($zone['enabled']) {
                $activeZones[] = new PricingZoneData(
                    id: $zone['id'],
                    name: $zone['name'],
                    surge_multiplier: $zone['surge_multiplier'],
                    is_active: $zone['surge_multiplier'] > 1.0,
                    reason: $zone['reason'],
                    center: $zone['center'],
                    radius_meters: $zone['radius_meters'],
                );
            }
        }

        return $activeZones;
    }
}
