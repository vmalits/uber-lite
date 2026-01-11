<?php

declare(strict_types=1);

namespace App\Data\Profile;

use Spatie\LaravelData\Data;

final class UpdateProfileData extends Data
{
    public function __construct(
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
    ) {}
}
