<?php

declare(strict_types=1);

namespace App\Services\Avatar;

use App\Enums\AvatarSize;
use App\Models\User;

interface AvatarUrlResolver
{
    public function getUrl(User $user, AvatarSize $size): ?string;

    /** @return array<string, string|null> */
    public function getAllUrls(User $user): array;
}
