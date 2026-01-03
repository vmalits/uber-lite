<?php

declare(strict_types=1);

namespace App\Jobs\Auth;

use App\Services\Sms\SmsServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Throwable;

final class SendOtpSmsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    /**
     * @var array<int>
     */
    public array $backoff = [10, 30, 60];

    public function __construct(
        private readonly string $phone,
        private readonly string $otpCode,
    ) {}

    public function handle(SmsServiceInterface $smsService, LoggerInterface $logger): void
    {
        $message = "Your OTP code is {$this->otpCode}";

        $logger->info('Sending OTP SMS', [
            'phone'   => $this->phone,
            'attempt' => $this->attempts(),
        ]);

        $success = $smsService->send($this->phone, $message);

        if (! $success) {
            $logger->warning('Failed to send OTP SMS', [
                'phone'   => $this->phone,
                'attempt' => $this->attempts(),
            ]);

            throw new \RuntimeException("Failed to send OTP SMS to {$this->phone}");
        }

        $logger->info('OTP SMS sent successfully', [
            'phone'   => $this->phone,
            'attempt' => $this->attempts(),
        ]);
    }

    public function failed(?Throwable $exception, LoggerInterface $logger): void
    {
        $logger->error('SendOtpSmsJob failed after all retries', [
            'phone'     => $this->phone,
            'attempts'  => $this->attempts(),
            'exception' => $exception?->getMessage(),
            'trace'     => $exception?->getTraceAsString(),
        ]);
    }

    /**
     * @return array<int>
     */
    public function backoff(): array
    {
        return $this->backoff;
    }
}
