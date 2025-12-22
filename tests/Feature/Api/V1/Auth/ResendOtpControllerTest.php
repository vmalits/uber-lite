<?php

declare(strict_types=1);

use App\Events\Auth\OtpResent;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function (): void {
    Event::fake([OtpResent::class]);
    RateLimiter::clear('otp:+37360000000');
});

it('successfully resends OTP code and dispatches event', function (): void {
    $phone = '+37360000000';

    $response = $this->postJson('/api/v1/auth/request-otp/resend', [
        'phone' => $phone,
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'data' => [
                'phone',
                'expires_at' => [
                    'human',
                    'string',
                ],
            ],
        ])
        ->assertJson([
            'message' => 'OTP has been resent successfully.',
            'data'    => [
                'phone' => $phone,
            ],
        ])
        ->assertHeader('X-RateLimit-Limit', '3')
        ->assertHeader('X-RateLimit-Remaining');

    $otpCode = OtpCode::query()->where('phone', $phone)->firstOrFail();

    expect($otpCode->expires_at->isFuture())->toBeTrue();

    Event::assertDispatched(OtpResent::class);
    Event::assertDispatchedTimes(OtpResent::class, 1);
});

it('rate limits resend OTP requests to 3 per 15 minutes per phone', function (): void {
    $phone = '+37360000000';

    // The first 3 requests should succeed
    for ($i = 0; $i < 3; $i++) {
        $this->postJson('/api/v1/auth/request-otp/resend', [
            'phone' => $phone,
        ])->assertOk();
    }

    // 4th request should be rate limited
    $response = $this->postJson('/api/v1/auth/request-otp/resend', [
        'phone' => $phone,
    ]);

    $response->assertStatus(429)
        ->assertJson([
            'message' => 'Too many OTP requests. Please try again later.',
        ])
        ->assertJsonStructure(['retry_after'])
        ->assertHeader('Retry-After')
        ->assertHeader('X-RateLimit-Limit', '3')
        ->assertHeader('X-RateLimit-Remaining', '0');

    Event::assertDispatchedTimes(OtpResent::class, 3);
});
