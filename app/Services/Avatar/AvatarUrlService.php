<?php

declare(strict_types=1);

namespace App\Services\Avatar;

use App\Enums\AvatarSize;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;

final readonly class AvatarUrlService implements AvatarUrlResolver
{
    public function __construct(
        private FilesystemAdapter $storage,
        private ?string $cdnUrl = null,
        private string $baseUrl = '',
    ) {}

    public function getUrl(User $user, AvatarSize $size): ?string
    {
        return $this->getUrlInternal($user, $size);
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
