<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Enums\RideStatus;
use App\Models\RideRating;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final class GetDriverReviewsQuery implements GetDriverReviewsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideRating>
     */
    public function execute(string $driverId, int $perPage): LengthAwarePaginator
    {
        $baseQuery = RideRating::query()
            ->with('rider')
            ->whereHas('ride', function (Builder $query) use ($driverId): void {
                $query->where('driver_id', $driverId)
                    ->where('status', RideStatus::COMPLETED);
            });

        /** @var QueryBuilder<RideRating> $query */
        $query = QueryBuilder::for($baseQuery)
            ->allowedSorts([
                AllowedSort::field('created_at'),
                AllowedSort::field('rating'),
            ])
            ->defaultSort('-created_at');

        return $query->paginate($perPage);
    }
}
