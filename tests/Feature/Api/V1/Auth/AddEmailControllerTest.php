<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;

it('adds email for a user with verified phone', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000000',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'email'             => null,
        'email_verified_at' => null,
    ]);

    $payload = [
        'phone' => $user->phone,
        'email' => 'user@gmail.com',
    ];

    $response = $this->postJson('/api/v1/auth/add-email', $payload);

    $response->assertOk()
        ->assertJson([
            'message' => 'Email added successfully.',
            'data'    => [
                'phone'        => $user->phone,
                'email'        => 'user@gmail.com',
                'profile_step' => ProfileStep::EMAIL_ADDED->value,
            ],
        ]);

    $user->refresh();

    expect($user->email)->toBe('user@gmail.com')
        ->and($user->email_verified_at)->toBeNull()
        ->and($user->profile_step)->toBe(ProfileStep::EMAIL_ADDED);
});

it('returns 422 when phone is not verified', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000001',
        'phone_verified_at' => null,
        'profile_step'      => null,
        'email'             => null,
    ]);

    $response = $this->postJson('/api/v1/auth/add-email', [
        'phone' => $user->phone,
        'email' => 'another@gmail.com',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

it('returns 422 when user does not exist', function (): void {
    $response = $this->postJson('/api/v1/auth/add-email', [
        'phone' => '+37369999999',
        'email' => 'ghost@gmail.com',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

it('validates email must be unique', function (): void {
    // Existing user with an email that will collide
    User::factory()->create([
        'phone' => '+37360000077',
        'email' => 'taken@gmail.com',
    ]);

    /** @var User $verified */
    $verified = User::factory()->create([
        'phone'             => '+37360000055',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
    ]);

    $response = $this->postJson('/api/v1/auth/add-email', [
        'phone' => $verified->phone,
        'email' => 'taken@gmail.com',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('validates email format', function (): void {
    /** @var User $verified */
    $verified = User::factory()->create([
        'phone'             => '+37360000066',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
    ]);

    $response = $this->postJson('/api/v1/auth/add-email', [
        'phone' => $verified->phone,
        'email' => 'not-an-email',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});
