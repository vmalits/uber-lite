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
            ],
        ])
        ->assertJson([
            'message' => __('messages.auth.otp_requested'),
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
    $response = $this->postJson('/api/v1/auth/request-otp', [
        'phone' => '123',
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

    $expectedExpiration = now()->addMinutes(5);

    expect($otpCode->expires_at->diffInMinutes($expectedExpiration))->toBeLessThan(1);
});

it('returns 409 when active OTP code exists', function (): void {
    $phone = '+37360000000';
    RateLimiter::clear('otp:'.$phone);

    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ])->assertOk();

    $response = $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ]);

    $response->assertStatus(409)
        ->assertJson([
            'message' => 'An active OTP code already exists.',
        ]);
});

it('rate limits OTP requests', function (): void {
    $phone = '+37360000000';

    // First request should succeed
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone,
    ])->assertOk();

    // Next two requests should return 409 (active OTP exists)
    for ($i = 0; $i < 2; $i++) {
        $this->postJson('/api/v1/auth/request-otp', [
            'phone' => $phone,
        ])->assertStatus(409)
            ->assertJson([
                'message' => 'An active OTP code already exists.',
            ]);
    }

    Event::assertDispatchedTimes(OtpRequested::class, 1);
});

it('rate limits are independent per phone number', function (): void {
    $phone1 = '+37360000000';
    $phone2 = '+37360000001';

    RateLimiter::clear('otp:'.$phone2);

    // First request for phone1 should succeed
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone1,
    ])->assertOk();
    // Second request for phone1 should return 409
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone1,
    ])->assertStatus(409);

    $this->travelTo(now()->addMinutes(7));
    // Next request for phone1 after expiration should succeed
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone1,
    ])->assertOk();
    // Next request for phone1 before expiration should return 429 (rate limit)
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone1,
    ])->assertStatus(429);

    // Request for phone2 should succeed (independent)
    $this->postJson('/api/v1/auth/request-otp', [
        'phone' => $phone2,
    ])->assertOk();

    Event::assertDispatchedTimes(OtpRequested::class, 3);
    Event::assertDispatched(OtpRequested::class, fn (OtpRequested $e) => $e->phone === $phone1);
    Event::assertDispatched(OtpRequested::class, fn (OtpRequested $e) => $e->phone === $phone2);
});
