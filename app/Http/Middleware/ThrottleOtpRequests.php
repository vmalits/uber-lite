<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
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

            /** @var \Illuminate\Http\JsonResponse $jsonResponse */
            $jsonResponse = response()->json([
                'message'     => 'Too many OTP requests. Please try again later.',
                'retry_after' => $seconds,
            ], Response::HTTP_TOO_MANY_REQUESTS);

            return $jsonResponse->withHeaders([
                'Retry-After'           => $seconds,
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
