<?php

declare(strict_types=1);

use App\Models\OtpCode;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function (): void {
    $this->smsService = $this->mock(SmsServiceInterface::class);
    RateLimiter::clear('otp:+37360000000');
});

it('successfully resends OTP code and sends SMS', function (): void {
    $phone = '+37360000000';

    $this->smsService
        ->shouldReceive('send')
        ->once()
        ->with($phone, Mockery::pattern('/Your OTP code is \d{6}/'))
        ->andReturn(true);

    $response = $this->postJson('/api/v1/auth/request-otp/resend', [
        'phone' => $phone,
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'data' => [
                'phone',
                'expires_at',
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
});

it('rate limits resend OTP requests to 3 per 15 minutes per phone', function (): void {
    $phone = '+37360000000';

    $this->smsService
        ->shouldReceive('send')
        ->times(3)
        ->andReturn(true);

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
});
