<?php

declare(strict_types=1);

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class FakeSmsService implements SmsServiceInterface
{
    public function send(string $to, string $message): bool
    {
        Log::info("Fake SMS sent to {$to}: {$message}");

        return true;
    }
}
