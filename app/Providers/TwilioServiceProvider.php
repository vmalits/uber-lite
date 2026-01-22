<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Sms\FakeSmsService;
use App\Services\Sms\SmsServiceInterface;
use App\Services\Sms\TwilioSmsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TwilioServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(
            SmsServiceInterface::class,
            function (Application $app) {
                $driver = config('services.sms.driver');

                return match ($driver) {
                    'twilio' => $app->make(TwilioSmsService::class),
                    default  => $app->make(FakeSmsService::class),
                };
            },
        );
    }

    public function boot(): void {}
}
