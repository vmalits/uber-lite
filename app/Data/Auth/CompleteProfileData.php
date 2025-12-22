<?php

declare(strict_types=1);

namespace App\Data\Auth;

use Spatie\LaravelData\Data;

final class CompleteProfileData extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
    ) {}
}
