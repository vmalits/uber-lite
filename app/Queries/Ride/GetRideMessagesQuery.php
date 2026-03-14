<?php

declare(strict_types=1);

namespace App\Queries\Ride;

use App\Models\Ride;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetRideMessagesQuery implements GetRideMessagesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, \App\Models\RideMessage>
     */
    public function execute(Ride $ride, int $perPage): LengthAwarePaginator
    {
        return $ride->messages()
            ->with(['sender'])
            ->oldest()
            ->paginate($perPage);
    }
}
