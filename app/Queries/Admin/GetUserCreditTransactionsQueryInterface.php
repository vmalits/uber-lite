<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\CreditTransaction;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetUserCreditTransactionsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, CreditTransaction>
     */
    public function execute(string $userId, int $perPage = 15): LengthAwarePaginator;
}
