<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Rider\GetPaymentStatusQuery;
use App\Queries\Rider\GetPaymentStatusQueryInterface;
use App\Services\Payment\FakePaymentService;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Support\ServiceProvider;

final class PaymentServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetPaymentStatusQueryInterface::class => GetPaymentStatusQuery::class,
    ];

    public function register(): void
    {
        $this->app->scoped(
            PaymentServiceInterface::class,
            function (): FakePaymentService {
                return $this->app->make(FakePaymentService::class);
            },
        );
    }
}
