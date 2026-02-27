<?php

declare(strict_types=1);

namespace App\Data\Safety;

use App\Models\EmergencyContact;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class EmergencyContactData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $phone,
        public ?string $email,
        #[MapName('is_primary')]
        public bool $isPrimary,
    ) {}

    public static function fromModel(EmergencyContact $contact): self
    {
        return new self(
            id: $contact->id,
            name: $contact->name,
            phone: $contact->phone,
            email: $contact->email,
            isPrimary: $contact->is_primary,
        );
    }
}
