<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Models\User;
use Spatie\LaravelData\Data;

final class CreditBalanceData extends Data
{
    public function __construct(
        public int $balance,
        public string $referral_code,
        public int $total_referrals,
        public int $total_earned,
    ) {}

    public static function fromUser(User $user): self
    {
        $totalReferrals = User::query()
            ->where('referred_by', $user->id)
            ->count();

        $totalEarned = (int) $user->creditTransactions()
            ->where('amount', '>', 0)
            ->sum('amount');

        return new self(
            balance: $user->credits_balance,
            referral_code: $user->referral_code ?? '',
            total_referrals: $totalReferrals,
            total_earned: $totalEarned,
        );
    }
}
