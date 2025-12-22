<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class GetRideHistoryQuery implements GetRideHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(User $user, int $perPage = 10): LengthAwarePaginator
    {
        $baseQuery = Ride::query()
            ->where('rider_id', $user->id)
            ->whereIn('status', [
                RideStatus::COMPLETED->value,
                RideStatus::CANCELLED->value,
            ]);

        /** @var QueryBuilder<Ride> $query */
        $query = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts([
                'created_at',
                'price',
            ])
            ->defaultSort('-created_at');

        /** @var LengthAwarePaginator<int, Ride> $paginator */
        $paginator = $query->paginate($perPage);

        return $paginator;
    }
}
