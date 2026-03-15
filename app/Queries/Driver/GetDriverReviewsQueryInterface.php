<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\RideRating;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetDriverReviewsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideRating>
     */
    public function execute(string $driverId, int $perPage): LengthAwarePaginator;
}
