<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetScheduledRidesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator;
}
