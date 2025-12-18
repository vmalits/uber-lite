<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\EmailVerificationRequested;
use App\Models\User;

final class SendEmailVerificationOnRequest
{
    public function handle(EmailVerificationRequested $event): void
    {
        /** @var User|null $user */
        $user = User::query()->find($event->userId);
        if ($user === null) {
            return;
        }

        $user->sendEmailVerificationNotification();
    }
}
