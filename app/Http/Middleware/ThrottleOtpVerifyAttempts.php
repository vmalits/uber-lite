<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final readonly class ThrottleOtpVerifyAttempts
{
    public function __construct(
        private ConfigRepository $config,
        private RateLimiter $rateLimiter,
    ) {}

    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        $phone = $request->input('phone');
        $ip = $request->ip();

        if ($phone === null || $ip === null) {
            /** @var Response $response */
            $response = $next($request);

            return $response;
        }

        /** @var int $maxAttempts */
        $maxAttempts = $this->config->get('otp.verify.rate_limit.max_attempts', 5);
        /** @var int $decayMinutes */
        $decayMinutes = $this->config->get('otp.verify.rate_limit.decay_minutes', 15);

        /** @var string $safePhone */
        $safePhone = $phone;
        $key = 'otp:verify:'.$ip.':'.$safePhone;

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

        /** @var Response $response */
        $response = $next($request);
        $remaining = $this->rateLimiter->remaining($key, $maxAttempts);

        return $response->withHeaders([
            'X-RateLimit-Limit'     => (string) $maxAttempts,
            'X-RateLimit-Remaining' => (string) $remaining,
        ]);
    }
}
