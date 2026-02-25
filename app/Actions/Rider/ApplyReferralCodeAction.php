<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class ApplyReferralCodeAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, string $referralCode): User
    {
        if ($user->referred_by !== null) {
            return $user;
        }

        $referrer = User::query()
            ->where('referral_code', strtoupper($referralCode))
            ->first();

        if (! $referrer || $referrer->id === $user->id) {
            return $user;
        }

        return $this->databaseManager->transaction(
            callback: function () use ($user, $referrer): User {
                $user->update([
                    'referred_by' => $referrer->id,
                    'referred_at' => now(),
                ]);

                return $user->refresh();
            },
            attempts: 3,
        );
    }
}
