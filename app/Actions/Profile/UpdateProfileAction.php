<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Data\Profile\UpdateProfileData;
use App\Models\User;

final readonly class UpdateProfileAction
{
    public function handle(User $user, UpdateProfileData $data): User
    {
        if ($data->firstName !== null) {
            $user->first_name = $data->firstName;
        }

        if ($data->lastName !== null) {
            $user->last_name = $data->lastName;
        }

        $user->save();

        return $user;
    }
}
