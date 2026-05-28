<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\PaymentStatus;
use App\Models\PaymentAttempt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetPaymentsQuery implements GetPaymentsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, PaymentAttempt>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = PaymentAttempt::query()
            ->with(['user']);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('status', static function (Builder $query, string $value) {
                    if (PaymentStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('ride_id'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'amount',
                'status',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
