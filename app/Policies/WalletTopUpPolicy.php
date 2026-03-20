<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\WalletTopUp;

final class WalletTopUpPolicy
{
    public function view(User $user, WalletTopUp $topUp): bool
    {
        return $topUp->user()->is($user);
    }

    public function confirm(User $user, WalletTopUp $topUp): bool
    {
        return $topUp->user()->is($user) && $topUp->isPending();
    }

    public function cancel(User $user, WalletTopUp $topUp): bool
    {
        return $topUp->user()->is($user) && $topUp->isPending();
    }
}
