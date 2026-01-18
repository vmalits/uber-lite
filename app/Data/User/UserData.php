<?php

declare(strict_types=1);

namespace App\Data\User;

use App\Data\DateData;
use App\Enums\Locale;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User as UserModel;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param string $first_name
 * @param string $last_name
 * @param string $phone
 * @param string|null $email
 * @param array|null $avatar_urls
 * @param UserRole|null $role
 * @param Locale|null $locale
 * @param ProfileStep|null $profile_step
 * @param UserStatus|null $status
 * @param DateData|null $phone_verified_at
 * @param DateData|null $email_verified_at
 * @param DateData|null $last_login_at
 * @param DateData|null $banned_at
 * @param DateData $created_at
 * @param DateData $updated_at
 */
final class UserData extends Data
{
    public function __construct(
        public string $id,
        public string $first_name,
        public string $last_name,
        public string $phone,
        public ?string $email,
        /** @var array<string, string|null> */
        public ?array $avatar_urls,
        public ?UserRole $role,
        public ?Locale $locale,
        public ?ProfileStep $profile_step,
        public ?UserStatus $status,
        public ?DateData $phone_verified_at,
        public ?DateData $email_verified_at,
        public ?DateData $last_login_at,
        public ?DateData $banned_at,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(UserModel $user, AvatarUrlResolver $avatarResolver): self
    {
        return new self(
            id: $user->id,
            first_name: $user->first_name ?? '',
            last_name: $user->last_name ?? '',
            phone: $user->phone,
            email: $user->email,
            avatar_urls: $avatarResolver->getAllUrls($user),
            role: $user->role,
            locale: $user->locale,
            profile_step: $user->profile_step,
            status: $user->status,
            phone_verified_at: $user->phone_verified_at ? DateData::fromCarbon($user->phone_verified_at) : null,
            email_verified_at: $user->email_verified_at ? DateData::fromCarbon($user->email_verified_at) : null,
            last_login_at: $user->last_login_at ? DateData::fromCarbon($user->last_login_at) : null,
            banned_at: $user->banned_at ? DateData::fromCarbon($user->banned_at) : null,
            created_at: DateData::fromCarbon($user->created_at),
            updated_at: DateData::fromCarbon($user->updated_at),
        );
    }
}
