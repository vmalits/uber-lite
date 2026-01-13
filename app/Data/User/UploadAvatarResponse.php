<?php

declare(strict_types=1);

namespace App\Data\User;

use App\Models\User;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Data;

final class UploadAvatarResponse extends Data
{
    public function __construct(
        public bool $processing,
        /** @var array<string, string|null> */
        public array $sizes,
    ) {}

    public static function fromUser(User $user, AvatarUrlResolver $avatarResolver): self
    {
        return new self(
            processing: true,
            sizes: $avatarResolver->getAllUrls($user),
        );
    }
}
