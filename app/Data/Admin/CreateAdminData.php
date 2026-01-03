<?php

declare(strict_types=1);

namespace App\Data\Admin;

use SensitiveParameter;
use Spatie\LaravelData\Data;

class CreateAdminData extends Data
{
    public function __construct(
        public readonly string $phone,
        public readonly ?string $email,
        #[SensitiveParameter]
        public readonly string $password,
    ) {}
}
