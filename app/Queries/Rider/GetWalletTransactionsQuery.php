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

final readonly class GetWalletTransactionsQuery implements GetWalletTransactionsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, CreditTransaction>
     */
    public function execute(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $baseQuery = CreditTransaction::query()
            ->where('user_id', $user->id)
            ->where('type', CreditTransactionType::WALLET_TOP_UP);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('from', static function (Builder $query, string $value): void {
                    $query->where('created_at', '>=', $value);
                }),
                AllowedFilter::callback('to', static function (Builder $query, string $value): void {
                    $query->where('created_at', '<=', $value.' 23:59:59');
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
