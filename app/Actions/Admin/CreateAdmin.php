<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\CreateAdminData;
use App\Enums\UserRole;
use App\Models\User;

class CreateAdmin
{
    public function handle(CreateAdminData $adminData): User
    {
        return User::create([
            'phone'             => $adminData->phone,
            'phone_verified_at' => now(),
            'email'             => $adminData->email,
            'email_verified_at' => now(),
            'password'          => $adminData->password,
            'role'              => UserRole::ADMIN,
        ]);
    }
}
