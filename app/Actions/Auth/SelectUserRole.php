<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Throwable;

final readonly class SelectUserRole
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, UserRole $role): bool
    {
        if ($user->role === $role) {
            return false;
        }

        $user->role = $role;

        return $user->save();
    }
}
