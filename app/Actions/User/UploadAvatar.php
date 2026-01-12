<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Data\User\UploadAvatarData;
use App\Enums\AvatarSize;
use App\Jobs\ProcessAvatarImage;
use App\Models\User;
use App\Services\Avatar\AvatarImagePipeline;
use Random\RandomException;

final readonly class UploadAvatar
{
    public function __construct(
        private AvatarImagePipeline $pipeline,
    ) {}

    /**
     * @throws RandomException
     *
     * @return array{processing: bool, sizes: array<int, AvatarSize>}
     */
    public function handle(User $user, UploadAvatarData $data): array
    {
        $tempFiles = $this->pipeline->process($data->avatar->getPathname());

        ProcessAvatarImage::dispatch($user, $tempFiles);

        return [
            'processing' => true,
            'sizes'      => AvatarSize::cases(),
        ];
    }
}
