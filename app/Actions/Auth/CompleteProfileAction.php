<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Exceptions\Auth\ProfileNotVerifiedException;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class CompleteProfileAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private CheckEmailVerifiedAction $emailVerifier,
    ) {}

    /**
     * @throws ProfileNotVerifiedException
     * @throws Throwable
     */
    public function handle(User $user, string $firstName, string $lastName): User
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $firstName, $lastName): User {
                $this->emailVerifier->validate($user);

                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->profile_step = ProfileStep::COMPLETED;

                $user->save();

                /** @var User $freshUser */
                $freshUser = $user->fresh();

                return $freshUser;
            },
            attempts: 3,
        );
    }
}
