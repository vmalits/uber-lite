<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class VerifyEmailAction
{
    public function __construct(private DatabaseManager $databaseManager) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user): void
    {
        $this->databaseManager->transaction(
            callback: function () use ($user): void {
                if (! $user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                if ($user->profile_step !== ProfileStep::COMPLETED) {
                    $user->profile_step = ProfileStep::EMAIL_VERIFIED;
                }

                $user->save();
            },
            attempts: 3);
    }
}
