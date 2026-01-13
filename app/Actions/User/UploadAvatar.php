<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Data\User\UploadAvatarData;
use App\Data\User\UploadAvatarResponse;
use App\Jobs\ProcessAvatarImage;
use App\Models\User;
use App\Services\Avatar\AvatarImagePipeline;
use App\Services\Avatar\AvatarUrlResolver;
use Random\RandomException;

final readonly class UploadAvatar
{
    public function __construct(
        private AvatarImagePipeline $pipeline,
        private AvatarUrlResolver $avatarUrlResolver,
    ) {}

    /**
     * @throws RandomException
     */
    public function handle(User $user, UploadAvatarData $data): UploadAvatarResponse
    {
        $tempFiles = $this->pipeline->process($data->avatar->getPathname());

        ProcessAvatarImage::dispatch($user, $tempFiles);

        return UploadAvatarResponse::fromUser($user, $this->avatarUrlResolver);
    }
}
