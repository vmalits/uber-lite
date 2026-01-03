<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Data;

final class AdminLoginData extends Data
{
    public function __construct(
        public string $phone,
        public string $password,
    ) {}
}
