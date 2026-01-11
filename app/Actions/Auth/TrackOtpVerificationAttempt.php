<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Psr\Log\LoggerInterface;

final readonly class TrackOtpVerificationAttempt
{
    public function __construct(
        private LoggerInterface $logger,
        private ConfigRepository $config,
        private RateLimiter $rateLimiter,
    ) {}

    public function trackFailedAttempt(string $phone): void
    {
        /** @var int $threshold */
        $threshold = $this->config->get('otp.failed_attempts.threshold', 3);
        /** @var int $decayMinutes */
        $decayMinutes = $this->config->get('otp.failed_attempts.decay_minutes', 10);
        /** @var int $blockDuration */
        $blockDuration = $this->config->get('otp.failed_attempts.block_duration', 15);

        $phoneKey = 'otp:verify:failed:'.$phone;
        $this->rateLimiter->hit($phoneKey, $decayMinutes * 60);

        $attempts = $this->rateLimiter->attempts($phoneKey);

        if ($attempts >= $threshold) {
            $blockKey = 'otp:verify:blocked:'.$phone;
            $this->rateLimiter->hit($blockKey, $blockDuration * 60);

            $this->logger->critical('SECURITY ALERT: OTP verification blocked due to multiple failed attempts', [
                'alert_type'     => 'otp_brute_force_detected',
                'phone'          => $phone,
                'attempts'       => $attempts,
                'threshold'      => $threshold,
                'blocked_until'  => now()->addMinutes($blockDuration)->toIso8601String(),
                'block_duration' => $blockDuration.' minutes',
                'action'         => 'Phone temporarily locked for OTP verification',
            ]);
        } else {
            $this->logger->info('OTP verification failed', [
                'phone'     => $phone,
                'attempts'  => $attempts,
                'threshold' => $threshold,
            ]);
        }
    }

    public function resetFailedAttempts(string $phone): void
    {
        $phoneKey = 'otp:verify:failed:'.$phone;
        $blockKey = 'otp:verify:blocked:'.$phone;

        $this->rateLimiter->clear($phoneKey);
        $this->rateLimiter->clear($blockKey);
    }

    public function isBlocked(string $phone): bool
    {
        $blockKey = 'otp:verify:blocked:'.$phone;

        return $this->rateLimiter->tooManyAttempts($blockKey, 1);
    }

    public function getBlockRemainingSeconds(string $phone): int
    {
        $blockKey = 'otp:verify:blocked:'.$phone;

        return $this->rateLimiter->availableIn($blockKey);
    }
}
