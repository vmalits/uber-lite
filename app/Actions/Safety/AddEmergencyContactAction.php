<?php

declare(strict_types=1);

namespace App\Actions\Safety;

use App\Data\Safety\CreateEmergencyContactData;
use App\Models\EmergencyContact;
use App\Models\User;

final readonly class AddEmergencyContactAction
{
    public function handle(User $user, CreateEmergencyContactData $data): EmergencyContact
    {
        return EmergencyContact::query()->create([
            'user_id'    => $user->id,
            'name'       => $data->name,
            'phone'      => $data->phone,
            'email'      => $data->email,
            'is_primary' => $data->isPrimary,
        ]);
    }
}
