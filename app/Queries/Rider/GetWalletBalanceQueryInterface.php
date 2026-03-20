<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\User;

interface GetWalletBalanceQueryInterface
{
    public function execute(User $user): int;
}
