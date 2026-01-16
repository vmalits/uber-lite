<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class AddEmailAction
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

                if (! $user->isPhoneVerified()) {
                    return false;
                }

                $user->email = $email;
                if (! $user->isProfileCompleted()) {
                    $user->profile_step = ProfileStep::EMAIL_ADDED;
                }

                return $user->save();
            },
            attempts: 3);
    }
}
