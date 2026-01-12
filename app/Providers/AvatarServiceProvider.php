<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Avatar\AvatarImagePipeline;
use App\Services\Avatar\AvatarUrlService;
use App\Services\Avatar\Storage\AvatarStorageService;
use App\Services\Cache\UserCacheService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AvatarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $disk = $this->getDisk();
        $cdnUrl = $this->getCdnUrl();
        $baseUrl = $this->getBaseUrl();

        $this->app->singleton(AvatarImagePipeline::class, function () {
            return new AvatarImagePipeline;
        });

        $this->app->singleton(AvatarStorageService::class, function () use ($disk) {
            return new AvatarStorageService(disk: $disk);
        });

        $this->app->singleton(
            AvatarUrlService::class, function (Application $app) use ($disk, $cdnUrl, $baseUrl) {
                /** @var FilesystemAdapter $filesystemAdapter */
                $filesystemAdapter = Storage::disk($disk);

                return new AvatarUrlService(
                    userCache: $app->make(UserCacheService::class),
                    storage: $filesystemAdapter,
                    cdnUrl: $cdnUrl,
                    baseUrl: $baseUrl,
                );
            });
    }

    public function boot(): void {}

    private function getDisk(): string
    {
        $disk = config('filesystems.default');

        return \is_string($disk) ? $disk : 'public';
    }

    private function getCdnUrl(): ?string
    {
        $cdnUrl = config('filesystems.cdn_url');

        return \is_string($cdnUrl) ? $cdnUrl : null;
    }

    private function getBaseUrl(): string
    {
        $url = config('app.url');

        return \is_string($url) ? $url : '';
    }
}
