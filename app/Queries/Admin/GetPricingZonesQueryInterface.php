<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\PricingZone;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetPricingZonesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, PricingZone>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
