<?php

declare(strict_types=1);

namespace App\Queries\Auth;

use App\Models\User;

final class FindUserByPhoneQuery implements FindUserByPhoneQueryInterface
{
    public function execute(string $phone): ?User
    {
        return User::query()
            ->where('phone', $phone)
            ->first();
    }
}
