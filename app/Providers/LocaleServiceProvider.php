<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\LocaleService;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class LocaleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(LocaleService::class);
    }
}
