<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class GetRideHistoryQuery implements GetRideHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(User $user, int $perPage = 15): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, Ride> $paginator */
        $paginator = Ride::query()
            ->where('rider_id', $user->id)
            ->latest()
            ->paginate($perPage);

        return $paginator;
    }
}
