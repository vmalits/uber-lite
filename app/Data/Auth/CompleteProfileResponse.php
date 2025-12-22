<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Models\User;
use Spatie\LaravelData\Data;

final class CompleteProfileResponse extends Data
{
    public function __construct(
        public string $id,
        public string $phone,
        public ?string $email,
        public string $first_name,
        public string $last_name,
        public ?string $role,
        public ?string $profile_step,
    ) {}

    public static function fromUser(User $user): self
    {
        return new self(
            id: $user->id,
            phone: $user->phone,
            email: $user->email,
            first_name: $user->first_name ?? '',
            last_name: $user->last_name ?? '',
            role: $user->role?->value,
            profile_step: $user->profile_step?->value,
        );
    }
}
