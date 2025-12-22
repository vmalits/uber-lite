<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class SelectUserRole
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, UserRole $role): User
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $role): User {
                /** @var User $user */
                $user = User::query()->lockForUpdate()->findOrFail($user->id);

                if ($user->role !== null) {
                    return $user;
                }

                $user->role = $role;
                $user->save();

                return $user;
            },
            attempts: 3,
        );
    }
}
