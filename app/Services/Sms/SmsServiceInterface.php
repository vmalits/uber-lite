<?php

declare(strict_types=1);

namespace App\Services\Sms;

interface SmsServiceInterface
{
    public function send(string $to, string $message): bool;
}
