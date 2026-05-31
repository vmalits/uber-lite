<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\PricingZone;

final readonly class DeletePricingZoneAction
{
    public function handle(PricingZone $zone): bool
    {
        return (bool) $zone->delete();
    }
}
