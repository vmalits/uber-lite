<?php

declare(strict_types=1);

use App\Events\Auth\OtpRequested;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;

beforeEach(function (): void {
    Event::fake([OtpRequested::class]);
    RateLimiter::clear('otp:+37360000000');
});

it('successfully requests OTP code and dispatches event', function (): void {
    $phone = '+37360000000';

    $response = $this->postJson('/api/v1/auth/request-otp', [
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
            'message' => 'OTP has been requested successfully.',
            'data'    => [
                'phone' => $phone,
            ],
        ])
        ->assertHeader('X-RateLimit-Limit', '3')
        ->assertHeader('X-RateLimit-Remaining');

    $otpCode = OtpCode::query()->where('phone', $phone)->firstOrFail();

    expect($otpCode->code)->toBeString()
        ->and($otpCode->expires_at)->toBeInstanceOf(Carbon\CarbonInterface::class)
        ->and($otpCode->expires_at->isFuture())->toBeTrue();

    Event::assertDispatched(OtpRequested::class);
    Event::assertDispatchedTimes(OtpRequested::class, 1);
});

it('validates phone number is required', function (): void {
    $response = $this->postJson('/api/v1/auth/request-otp', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

it('validates phone number format for Moldova', function (): void {
    $response = $this->postJson('/api/v1/auth/request-otp', [
        'phone' => 'invalid-phone',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

it('validates phone number format', function (): void {
    // Test with a clearly invalid phone format
    $response = $this->postJson('/api/v1/auth/request-otp', [
        'phone' => '123', // Too short and invalid format
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

it('creates OTP code with correct expiration time', function (): void {
    $phone = '+37360000000';

    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ]);

    $otpCode = OtpCode::query()->where('phone', $phone)->firstOrFail();

    $expectedExpiration = now()->addMinutes(1);

    expect($otpCode->expires_at->diffInMinutes($expectedExpiration))->toBeLessThan(1);
});

it('generates unique OTP codes for same phone', function (): void {
    $phone = '+37360000000';

    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ]);

    $firstOtpId = OtpCode::query()->where('phone', $phone)->latest('id')->value('id');
    $firstOtpCode = OtpCode::query()->where('phone', $phone)->latest('id')->value('code');

    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ]);

    $secondOtpId = OtpCode::query()->where('phone', $phone)->latest('id')->value('id');
    $secondOtpCode = OtpCode::query()->where('phone', $phone)->latest('id')->value('code');

    // Codes should be different (very unlikely to be the same with 6 digits)
    expect($firstOtpId)->not->toBe($secondOtpId)
        ->and($firstOtpCode)->not->toBe($secondOtpCode);
});

it('returns expires_at as DateData', function (): void {
    $phone = '+37360000000';

    $response = $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ]);

    $response->assertOk();

    $data = $response->json('data');

    expect($data['expires_at'])->toBeArray()
        ->and($data['expires_at'])->toHaveKeys(['human', 'string'])
        ->and($data['expires_at']['human'])->toBeString()
        ->and($data['expires_at']['string'])->toBeString();
});

it('rate limits OTP requests to 3 per 15 minutes per phone', function (): void {
    $phone = '+37360000000';

    // The first 3 requests should succeed
    for ($i = 0; $i < 3; $i++) {
        $response = $this->postJson('/api/v1/auth/request-otp', [
            'phone' => $phone,
        ]);

        $response->assertOk();
    }

    // 4th request should be rate limited
    $response = $this->postJson('/api/v1/auth/request-otp', [
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

    Event::assertDispatchedTimes(OtpRequested::class, 3);
});

it('rate limits are independent per phone number', function (): void {
    $phone1 = '+37360000000';
    $phone2 = '+37360000001';

    RateLimiter::clear('otp:'.$phone2);

    // Make 3 requests for phone1
    for ($i = 0; $i < 3; $i++) {
        $this->postJson('/api/v1/auth/request-otp', [
            'phone' => $phone1,
        ])->assertOk();
    }

    // phone1 should be rate-limited (this won't call SMS service)
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone1,
    ])->assertStatus(429);

    // phone2 should still work
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone2,
    ])->assertOk();

    // Total dispatched events should be 4 (3 for phone1, 1 for phone2)
    Event::assertDispatchedTimes(OtpRequested::class, 4);
    // Ensure at least one event per each phone
    Event::assertDispatched(OtpRequested::class, fn (OtpRequested $e) => $e->phone === $phone1);
    Event::assertDispatched(OtpRequested::class, fn (OtpRequested $e) => $e->phone === $phone2);
});
