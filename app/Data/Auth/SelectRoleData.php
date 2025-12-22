<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Enums\UserRole;
use Spatie\LaravelData\Data;

final class SelectRoleData extends Data
{
    public function __construct(
        public UserRole $role,
    ) {}
}
