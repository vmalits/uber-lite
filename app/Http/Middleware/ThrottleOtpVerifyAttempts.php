<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class ThrottleOtpVerifyAttempts
{
    public function __construct(
        private ConfigRepository $config,
        private RateLimiter $rateLimiter,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $phone = $request->input('phone');
        $ip = $request->ip();

        if ($phone === null || $ip === null) {
            /** @phpstan-ignore return.type */
            return $next($request);
        }

        /** @var int $maxAttempts */
        $maxAttempts = $this->config->get('otp.verify.rate_limit.max_attempts', 5);
        /** @var int $decayMinutes */
        $decayMinutes = $this->config->get('otp.verify.rate_limit.decay_minutes', 15);

        /** @phpstan-ignore binaryOp.invalid */
        $key = 'otp:verify:'.$ip.':'.$phone;

        if ($this->rateLimiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->rateLimiter->availableIn($key);

            $jsonResponse = ApiResponse::tooManyRequests(
                'Too many verification attempts. Please try again later.',
                $seconds,
            );

            return $jsonResponse->withHeaders([
                'Retry-After'           => (string) $seconds,
                'X-RateLimit-Limit'     => (string) $maxAttempts,
                'X-RateLimit-Remaining' => '0',
            ]);
        }

        $this->rateLimiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        $remaining = $this->rateLimiter->remaining($key, $maxAttempts);

        /** @phpstan-ignore method.nonObject, return.type */
        return $response->withHeaders([
            'X-RateLimit-Limit'     => (string) $maxAttempts,
            'X-RateLimit-Remaining' => (string) $remaining,
        ]);
    }
}
