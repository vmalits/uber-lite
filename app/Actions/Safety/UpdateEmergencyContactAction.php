<?php

declare(strict_types=1);

namespace App\Actions\Safety;

use App\Data\Safety\UpdateEmergencyContactData;
use App\Models\EmergencyContact;

final readonly class UpdateEmergencyContactAction
{
    public function handle(EmergencyContact $contact, UpdateEmergencyContactData $data): EmergencyContact
    {
        $updateData = [];

        if ($data->name !== null) {
            $updateData['name'] = $data->name;
        }

        if ($data->phone !== null) {
            $updateData['phone'] = $data->phone;
        }

        if ($data->email !== null) {
            $updateData['email'] = $data->email;
        }

        if ($data->isPrimary !== null) {
            $updateData['is_primary'] = $data->isPrimary;
        }

        if ($updateData !== []) {
            $contact->update($updateData);
        }

        return $contact;
    }
}
