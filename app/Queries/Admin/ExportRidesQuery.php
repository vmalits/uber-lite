<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Dto\Admin\RideExportFilter;
use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class ExportRidesQuery implements ExportRidesQueryInterface
{
    /**
     * @return LazyCollection<int, Ride>
     */
    public function execute(RideExportFilter $filter): LazyCollection
    {
        $baseQuery = Ride::query()
            ->with(['rider', 'driver']);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('status', static function (Builder $query, string $value): void {
                    if (RideStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
                AllowedFilter::exact('rider_id'),
                AllowedFilter::exact('driver_id'),
                AllowedFilter::callback('date_from', static function (Builder $query, string $value): void {
                    $query->whereDate('created_at', '>=', $value);
                }),
                AllowedFilter::callback('date_to', static function (Builder $query, string $value): void {
                    $query->whereDate('created_at', '<=', $value);
                }),
            ])
            ->defaultSort('-created_at')
            ->get()
            ->lazy();
    }
}
