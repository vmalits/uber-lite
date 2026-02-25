<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Collection;

interface GetCreditBalanceQueryInterface
{
    /**
     * @return Collection<int, CreditTransaction>
     */
    public function execute(User $user, int $limit): Collection;
}
