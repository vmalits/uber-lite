<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetDriverReviewsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, \App\Models\RideRating>
     */
    public function execute(User $driver, int $perPage): LengthAwarePaginator;
}
