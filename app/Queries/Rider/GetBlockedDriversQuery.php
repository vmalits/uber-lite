<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\BlockedDriver;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GetBlockedDriversQuery implements GetBlockedDriversQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, BlockedDriver>
     */
    public function execute(User $rider, int $perPage): LengthAwarePaginator
    {
        $query = BlockedDriver::query()
            ->where('rider_id', $rider->id)
            ->with('driver')
            ->orderBy('created_at', 'desc');

        /** @var QueryBuilder<BlockedDriver> $qb */
        $qb = QueryBuilder::for($query);

        return $qb->paginate($perPage);
    }
}
