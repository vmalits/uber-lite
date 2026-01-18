<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Ride;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetRidesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
