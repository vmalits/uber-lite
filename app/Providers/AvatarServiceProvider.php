<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Avatar\AvatarImagePipeline;
use App\Services\Avatar\AvatarUrlResolver;
use App\Services\Avatar\AvatarUrlService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class AvatarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $cdnUrl = $this->getCdnUrl();
        $baseUrl = $this->getBaseUrl();

        $this->app->singleton(AvatarImagePipeline::class, function () {
            return new AvatarImagePipeline(
                manager: new ImageManager(new Driver),
                optimizer: OptimizerChainFactory::create(),
            );
        });

        $this->app->singleton(AvatarUrlService::class, function (Application $app) use ($cdnUrl, $baseUrl) {
            $disk = $this->getDisk();

            return new AvatarUrlService(
                storage: Storage::disk($disk),
                cdnUrl: $cdnUrl,
                baseUrl: $baseUrl,
            );
        });

        $this->app->bind(AvatarUrlResolver::class, AvatarUrlService::class);
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
