<?php

declare(strict_types=1);

namespace App\Services\Avatar;

use App\Enums\AvatarSize;
use App\Models\User;
use App\Services\Cache\UserCacheService;
use Illuminate\Filesystem\FilesystemAdapter;

final readonly class AvatarUrlService implements AvatarUrlResolver
{
    /**
     * @param UserCacheService<string|null> $userCache
     */
    public function __construct(
        private UserCacheService $userCache,
        private FilesystemAdapter $storage,
        private ?string $cdnUrl = null,
        private string $baseUrl = '',
    ) {}

    public function getUrl(User $user, AvatarSize $size): ?string
    {
        return $this->userCache->rememberAvatarUrl(
            $user,
            $size->value,
            fn () => $this->getUrlInternal($user, $size),
        );
    }

    /** @return array<string, string|null> */
    public function getAllUrls(User $user): array
    {
        $urls = [];

        foreach (AvatarSize::cases() as $size) {
            $urls[$size->value] = $this->getUrl($user, $size);
        }

        return $urls;
    }

    public function invalidate(User $user): void
    {
        $this->userCache->forgetAvatarUrls($user);
    }

    /** @return array<string, string|null> */
    public function refresh(User $user): array
    {
        $this->invalidate($user);

        return $this->getAllUrls($user);
    }

    private function getUrlInternal(User $user, AvatarSize $size): ?string
    {
        $paths = $user->avatar_paths ?? [];

        if (! isset($paths[$size->value])) {
            return null;
        }

        $path = $paths[$size->value];

        if ($this->cdnUrl) {
            return rtrim($this->cdnUrl, '/').'/'.$path;
        }

        $url = $this->storage->url($path);

        if ($this->baseUrl && str_starts_with($url, '/')) {
            return $this->baseUrl.$url;
        }

        return $url;
    }
}
