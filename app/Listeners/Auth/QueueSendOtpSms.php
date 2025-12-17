<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Events\Auth\OtpRequested;
use App\Events\Auth\OtpResent;
use App\Jobs\Auth\SendOtpSmsJob;

final class QueueSendOtpSms
{
    public function handle(OtpRequested|OtpResent $event): void
    {
        SendOtpSmsJob::dispatch($event->phone, $event->otpCode);
    }
}
