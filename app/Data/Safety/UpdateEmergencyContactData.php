<?php

declare(strict_types=1);

namespace App\Data\Safety;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class UpdateEmergencyContactData extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?string $phone = null,
        public ?string $email = null,
        #[MapName('is_primary')]
        public ?bool $isPrimary = null,
    ) {}
}
