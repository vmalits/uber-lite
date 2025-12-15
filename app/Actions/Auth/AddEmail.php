<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\User;
use App\Queries\Auth\FindUserByPhoneQuery;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class AddEmail
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private FindUserByPhoneQuery $findUserByPhone,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(string $phone, string $email): bool
    {
        return $this->databaseManager->transaction(
            callback: function () use ($phone, $email): bool {
                /** @var User|null $user */
                $user = $this->findUserByPhone->execute($phone);
                if ($user === null) {
                    return false;
                }

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
