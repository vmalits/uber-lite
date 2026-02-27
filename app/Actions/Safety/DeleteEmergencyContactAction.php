<?php

declare(strict_types=1);

namespace App\Actions\Safety;

use App\Models\EmergencyContact;

final readonly class DeleteEmergencyContactAction
{
    public function handle(EmergencyContact $contact): void
    {
        $contact->delete();
    }
}
