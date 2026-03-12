<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetCreditTransactionsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, CreditTransaction>
     */
    public function execute(User $user, int $perPage = 15): LengthAwarePaginator;
}
