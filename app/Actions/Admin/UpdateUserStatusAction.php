<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\UserStatus;
use App\Models\User;

final readonly class UpdateUserStatusAction
{
    public function handle(User $user, UserStatus $status): User
    {
        $user->update([
            'status'    => $status,
            'banned_at' => $status === UserStatus::BANNED ? now() : null,
        ]);

        return $user->refresh();
    }
}
