<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class GetRideHistoryQuery implements GetRideHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        $baseQuery = Ride::query()
            ->with('rating')
            ->where('driver_id', $user->id)
            ->whereIn('status', [
                RideStatus::COMPLETED,
                RideStatus::CANCELLED,
            ]);

        /** @var QueryBuilder<Ride> $query */
        $query = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('status', function (Builder $query, string $value) {
                    if (\in_array($value, [
                        RideStatus::COMPLETED->value,
                        RideStatus::CANCELLED->value,
                    ], true)) {
                        $query->where('status', $value);
                    }
                }),
            ])
            ->allowedSorts([
                'created_at',
                'price',
            ])
            ->defaultSort('-created_at');

        return $query->paginate($perPage);
    }
}
