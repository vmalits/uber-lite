<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GetScheduledRidesQuery implements GetScheduledRidesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        $baseQuery = Ride::query()
            ->where('rider_id', $user->id)
            ->where('status', RideStatus::SCHEDULED);

        /** @var QueryBuilder<Ride> $query */
        $query = QueryBuilder::for($baseQuery)
            ->allowedSorts([
                'scheduled_at',
                'created_at',
            ])
            ->defaultSort('scheduled_at');

        return $query->paginate($perPage);
    }
}
