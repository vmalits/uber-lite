<?php

declare(strict_types=1);

namespace App\Services\Sms;

use Psr\Log\LoggerInterface;

final readonly class FakeSmsService implements SmsServiceInterface
{
    public function __construct(private LoggerInterface $logger) {}

    public function send(string $to, string $message): bool
    {
        $this->logger->info("Fake SMS sent to {$to}: {$message}");

        return true;
    }
}
