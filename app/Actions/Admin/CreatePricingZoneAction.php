<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\PricingZone\PricingZoneInputData;
use App\Models\PricingZone;

final readonly class CreatePricingZoneAction
{
    public function handle(PricingZoneInputData $data): PricingZone
    {
        /** @var PricingZone $zone */
        $zone = PricingZone::query()->create([
            'name'             => $data->name,
            'slug'             => $data->slug,
            'is_enabled'       => $data->is_enabled,
            'surge_multiplier' => $data->surge_multiplier,
            'reason'           => $data->reason,
            'center_lat'       => $data->center_lat,
            'center_lng'       => $data->center_lng,
            'radius_meters'    => $data->radius_meters,
        ]);

        return $zone;
    }
}
