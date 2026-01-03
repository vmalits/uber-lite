<?php

declare(strict_types=1);

namespace App\Services\Sms;

use Exception;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

class TwilioSmsService
{
    private string $from;

    public function __construct(
        private readonly Client $client,
        private readonly LoggerInterface $logger,
    ) {
        /** @var string $from */
        $from = config('services.sms.twilio.from');
        $this->from = $from;
    }

    public function send(string $to, string $message): bool
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);

            return true;
        } catch (Exception $e) {
            $this->logger->error('Twilio SMS error: '.$e->getMessage());

            return false;
        }
    }
}
