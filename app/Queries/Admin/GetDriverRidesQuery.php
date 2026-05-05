<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetDriverRidesQuery implements GetDriverRidesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Ride>
     */
    public function execute(string $driverId, int $perPage): LengthAwarePaginator
    {
        $baseQuery = Ride::query()
            ->where('driver_id', $driverId)
            ->with(['rider', 'driver', 'rating']);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('status', static function (Builder $query, string $value) {
                    if (RideStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'price',
                'status',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
