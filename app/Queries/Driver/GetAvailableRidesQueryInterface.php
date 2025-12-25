<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\Ride;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetAvailableRidesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
