<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\PricingZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetPricingZonesQuery implements GetPricingZonesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, PricingZone>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = PricingZone::query();

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('is_enabled', static function (Builder $query, mixed $value): void {
                    $isEnabled = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isEnabled !== null) {
                        $query->where('is_enabled', $isEnabled);
                    }
                }),
                AllowedFilter::callback('name', static function (Builder $query, string $value): void {
                    $query->where('name', 'like', '%'.$value.'%');
                }),
                AllowedFilter::callback('slug', static function (Builder $query, string $value): void {
                    $query->where('slug', 'like', '%'.$value.'%');
                }),
                AllowedFilter::callback('reason', static function (Builder $query, string $value): void {
                    $query->where('reason', 'like', '%'.$value.'%');
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'name',
                'surge_multiplier',
                'radius_meters',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
