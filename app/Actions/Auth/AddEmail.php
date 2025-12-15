<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class AddEmail
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, string $email): bool
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $email): bool {

                $step = $user->profile_step?->value;
                $phoneVerified = $user->phone_verified_at !== null || $step === ProfileStep::PHONE_VERIFIED->value;
                if (! $phoneVerified) {
                    return false;
                }

                $user->email = $email;
                if ($step !== ProfileStep::COMPLETED->value) {
                    $user->profile_step = ProfileStep::EMAIL_ADDED;
                }

                return $user->save();
            },
            attempts: 3);
    }
}
