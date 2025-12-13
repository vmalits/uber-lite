<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleOtpRequests
{
    private const int MAX_ATTEMPTS = 3;

    private const int DECAY_MINUTES = 15;

    public function handle(Request $request, Closure $next): Response
    {
        /** @var string|null $phone */
        $phone = $request->input('phone');

        if ($phone === null) {
            /** @var Response $response */
            $response = $next($request);

            return $response;
        }

        $key = 'otp:'.$phone;

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);

            /** @var JsonResponse $jsonResponse */
            $jsonResponse = ApiResponse::tooManyRequests(
                'Too many OTP requests. Please try again later.', $seconds,
            );

            return $jsonResponse->withHeaders([
                'Retry-After'           => (string) $seconds,
                'X-RateLimit-Limit'     => (string) self::MAX_ATTEMPTS,
                'X-RateLimit-Remaining' => '0',
            ]);
        }

        RateLimiter::hit($key, self::DECAY_MINUTES * 60);

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        $remaining = RateLimiter::remaining($key, self::MAX_ATTEMPTS);

        return $response->withHeaders([
            'X-RateLimit-Limit'     => (string) self::MAX_ATTEMPTS,
            'X-RateLimit-Remaining' => (string) $remaining,
        ]);
    }
}
