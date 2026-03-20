<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Enums\WalletTopUpStatus;
use App\Models\User;
use App\Models\WalletTopUp;

final readonly class GetWalletBalanceQuery implements GetWalletBalanceQueryInterface
{
    public function execute(User $user): int
    {
        return WalletTopUp::query()
            ->where('user_id', $user->id)
            ->where('status', WalletTopUpStatus::PENDING)
            ->count();
    }
}
