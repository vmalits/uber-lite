<?php

declare(strict_types=1);

namespace App\Data\Safety;

use Spatie\LaravelData\Data;

final class CreateEmergencyContactData extends Data
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $email = null,
        public bool $isPrimary = false,
    ) {}
}
