<?php

declare(strict_types=1);

namespace App\Observers;

use App\Cache\UserCache;
use App\Models\User;

final readonly class UserObserver
{
    public function updated(User $user): void
    {
        $user->wasChanged([
            'first_name',
            'last_name',
            'email',
            'avatar_path',
            'role',
            'profile_step',
            'status',
            'banned_at',
        ]) && app(UserCache::class)->invalidate($user);
    }
}
