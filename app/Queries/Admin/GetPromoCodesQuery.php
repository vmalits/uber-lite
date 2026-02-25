<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\DiscountType;
use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetPromoCodesQuery implements GetPromoCodesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, PromoCode>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = PromoCode::query();

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('code', static function (Builder $query, string $value): void {
                    $query->where('code', 'like', '%'.strtoupper($value).'%');
                }),
                AllowedFilter::callback('discount_type', static function (Builder $query, string $value): void {
                    if (DiscountType::tryFrom($value) !== null) {
                        $query->where('discount_type', $value);
                    }
                }),
                AllowedFilter::callback('is_active', static function (Builder $query, mixed $value): void {
                    $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isActive !== null) {
                        $query->where('is_active', $isActive);
                    }
                }),
                AllowedFilter::callback('valid', static function (Builder $query, mixed $value): void {
                    $isValid = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isValid === true) {
                        $query->where('is_active', true)
                            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
                            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
                    }
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'code',
                'used_count',
                'expires_at',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
