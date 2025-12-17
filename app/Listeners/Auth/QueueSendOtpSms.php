<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\OtpRequested;
use App\Events\Auth\OtpResent;
use App\Jobs\Auth\SendOtpSmsJob;
use Illuminate\Contracts\Bus\Dispatcher;

final readonly class QueueSendOtpSms
{
    public function __construct(private Dispatcher $dispatcher) {}

    public function handle(OtpRequested|OtpResent $event): void
    {
        $this->dispatcher->dispatch(
            new SendOtpSmsJob(
                phone: $event->phone,
                otpCode: $event->otpCode,
            ),
        );
    }
}
