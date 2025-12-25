<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class GetAvailableRidesQuery implements GetAvailableRidesQueryInterface
{
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = Ride::query()
            ->where('status', RideStatus::PENDING)
            ->whereNull('driver_id');

        return QueryBuilder::for($baseQuery)
            ->allowedSorts(['created_at', 'price'])
            ->defaultSort('created_at')
            ->paginate($perPage);
    }
}
