<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Collection;

final class GetCreditBalanceQuery implements GetCreditBalanceQueryInterface
{
    /**
     * @return Collection<int, CreditTransaction>
     */
    public function execute(User $user, int $limit = 20): Collection
    {
        return $user->creditTransactions()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
