<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use Spatie\LaravelData\Data;

final class SelectRoleResponse extends Data
{
    public function __construct(
        public string $role,
        public string $profile_step,
    ) {}

    public static function of(UserRole $role, ProfileStep $profileStep): self
    {
        return new self(
            role: $role->value,
            profile_step: $profileStep->value,
        );
    }
}
