<?php

declare(strict_types=1);

namespace App\Data\User;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Data;

final class ProfileResponse extends Data
{
    public function __construct(
        public string $id,
        public string $phone,
        public ?string $email,
        public string $first_name,
        public string $last_name,
        /** @var array<string, string|null> */
        public ?array $avatar_urls,
        public ?UserRole $role,
        public ?ProfileStep $profile_step,
        public ?UserStatus $status,
    ) {}

    public static function fromUser(User $user, AvatarUrlResolver $avatarResolver): self
    {
        return new self(
            id: $user->id,
            phone: $user->phone,
            email: $user->email,
            first_name: $user->first_name ?? '',
            last_name: $user->last_name ?? '',
            avatar_urls: $avatarResolver->getAllUrls($user),
            role: $user->role,
            profile_step: $user->profile_step,
            status: $user->status,
        );
    }
}
