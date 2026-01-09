<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Data\User\UploadAvatarData;
use App\Models\User;
use App\Services\AvatarStorageService;

final readonly class UploadAvatar
{
    public function __construct(
        private AvatarStorageService $avatarStorageService,
    ) {}

    public function handle(User $user, UploadAvatarData $data): string
    {
        $path = $this->avatarStorageService->store($user, $data->avatar);

        $user->update([
            'avatar_path' => $path,
        ]);

        return $path;
    }
}
