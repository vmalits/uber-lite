<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\OtpCode;
use App\Models\User;

function createOtp(string $phone, string $code, DateTimeInterface $expiresAt, bool $used = false): OtpCode
{
    return OtpCode::query()->create([
        'phone'      => $phone,
        'code'       => $code,      // will be hashed by cast
        'expires_at' => $expiresAt,
        'used'       => $used,
    ]);
}

it('verifies OTP successfully and updates user', function (): void {
    $phone = '+37360000000';
    $code = '123456';

    // Given a valid, unused OTP that is not expired
    createOtp($phone, $code, now()->addMinutes(5));

    // When
    $response = $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => $phone,
        'code'  => $code,
    ]);

    // Then
    $response->assertOk()
        ->assertJson([
            'message' => 'OTP verified successfully.',
            'data'    => [
                'profile_step' => ProfileStep::PHONE_VERIFIED->value,
                'token_type'   => 'Bearer',
            ],
        ])
        ->assertJsonStructure([
            'data' => ['token'],
        ]);

    // OTP is marked used
    $otp = OtpCode::query()->where('phone', $phone)->latest('id')->firstOrFail();
    expect($otp->used)->toBeTrue();

    // User is created/updated accordingly
    /** @var User $user */
    $user = User::query()->where('phone', $phone)->firstOrFail();
    expect($user->phone_verified_at)->not->toBeNull()
        ->and($user->last_login_at)->not->toBeNull()
        ->and($user->profile_step)->toBe(ProfileStep::PHONE_VERIFIED);
});

it('returns validation error when code is invalid', function (): void {
    $phone = '+37360000001';

    // Stored OTP is different from the one we submit
    createOtp($phone, '654321', now()->addMinutes(5));

    $response = $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => $phone,
        'code'  => '000000',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['code'])
        ->assertJsonPath('errors.code.0', 'Invalid or expired code.');
});

it('returns validation error when code is expired', function (): void {
    $phone = '+37360000002';
    $code = '111111';

    createOtp($phone, $code, now()->subMinute()); // expired

    $response = $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => $phone,
        'code'  => $code,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['code'])
        ->assertJsonPath('errors.code.0', 'Invalid or expired code.');
});

it('returns validation error when code was already used', function (): void {
    $phone = '+37360000003';
    $code = '222222';

    createOtp($phone, $code, now()->addMinutes(5), used: true);

    $response = $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => $phone,
        'code'  => $code,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['code'])
        ->assertJsonPath('errors.code.0', 'Invalid or expired code.');
});

it('validates required fields and formats', function (): void {
    // Missing both
    $this->postJson('/api/v1/auth/verify-otp', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['phone', 'code']);

    // Invalid phone format
    $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => 'invalid',
        'code'  => '123456',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);

    // Code size must be 6
    $this->postJson('/api/v1/auth/verify-otp', [
        'phone' => '+37360000000',
        'code'  => '12345',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['code']);
});
