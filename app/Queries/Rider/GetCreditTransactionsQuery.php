<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Enums\CreditTransactionType;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetCreditTransactionsQuery implements GetCreditTransactionsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, CreditTransaction>
     */
    public function execute(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $baseQuery = CreditTransaction::query()
            ->where('user_id', $user->id);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('type', static function (Builder $query, string $value): void {
                    if (CreditTransactionType::tryFrom($value) !== null) {
                        $query->where('type', $value);
                    }
                }),
                AllowedFilter::callback('from', static function (Builder $query, string $value): void {
                    $query->where('created_at', '>=', $value);
                }),
                AllowedFilter::callback('to', static function (Builder $query, string $value): void {
                    $query->where('created_at', '<=', $value.' 23:59:59');
                }),
                AllowedFilter::callback('direction', static function (Builder $query, string $value): void {
                    if ($value === 'credit') {
                        $query->where('amount', '>', 0);
                    } elseif ($value === 'debit') {
                        $query->where('amount', '<', 0);
                    }
                }),
            ])
            ->allowedSorts([
                'created_at',
                'amount',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
