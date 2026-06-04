<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\BlockedDriver;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetBlockedDriversQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, BlockedDriver>
     */
    public function execute(User $rider, int $perPage): LengthAwarePaginator;
}
