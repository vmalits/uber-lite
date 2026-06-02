<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\NearbyDriverData;
use App\Data\Rider\NearbyDriversRequestData;
use Illuminate\Support\Collection;

interface GetNearbyDriversQueryInterface
{
    /**
     * @return Collection<int, NearbyDriverData>
     */
    public function execute(NearbyDriversRequestData $data): Collection;
}
