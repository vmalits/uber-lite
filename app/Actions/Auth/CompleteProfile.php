<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class CompleteProfile
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, string $firstName, string $lastName): bool
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $firstName, $lastName): bool {
                $step = $user->profile_step?->value;
                $emailVerified = $user->hasVerifiedEmail()
                    || $step === ProfileStep::EMAIL_VERIFIED->value
                    || $step === ProfileStep::COMPLETED->value;
                if (! $emailVerified) {
                    return false;
                }

                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->profile_step = ProfileStep::COMPLETED;

                return $user->save();
            },
            attempts: 3);
    }
}
