<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Data;

final class AdminLoginResponseData extends Data
{
    public function __construct(
        public string $token,
    ) {}
}
