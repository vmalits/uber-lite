<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\EmailAdded;
use App\Models\User;

final class SendEmailVerificationOnEmailAdded
{
    public function handle(EmailAdded $event): void
    {
        /** @var User|null $user */
        $user = User::query()->find($event->userId);
        if ($user === null) {
            return;
        }

        $user->sendEmailVerificationNotification();
    }
}
