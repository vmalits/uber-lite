<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Enums\RideStatus;
use App\Models\RideRating;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final class GetDriverReviewsQuery implements GetDriverReviewsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideRating>
     */
    public function execute(User $driver, int $perPage): LengthAwarePaginator
    {
        $baseQuery = RideRating::query()
            ->with('rider')
            ->whereHas('ride', function (Builder $query) use ($driver): void {
                $query->where('driver_id', $driver->id)
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
