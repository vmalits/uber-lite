<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('completes profile for a user with verified email', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000111',
        'email'             => 'john@example.com',
        'email_verified_at' => now(),
        'profile_step'      => ProfileStep::EMAIL_VERIFIED,
        'first_name'        => null,
        'last_name'         => null,
    ]);

    $payload = [
        'first_name' => 'John',
        'last_name'  => 'Doe',
    ];

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/auth/complete-profile', $payload);

    $response->assertOk()
        ->assertJson([
            'message' => 'Profile completed successfully.',
            'data'    => [
                'id'           => $user->id,
                'phone'        => $user->phone,
                'email'        => $user->email,
                'first_name'   => 'John',
                'last_name'    => 'Doe',
                'role'         => $user->role?->value,
                'profile_step' => ProfileStep::COMPLETED->value,
            ],
        ]);

    $user->refresh();

    expect($user->first_name)->toBe('John')
        ->and($user->last_name)->toBe('Doe')
        ->and($user->profile_step)->toBe(ProfileStep::COMPLETED);
});

it('returns 403 when email is not verified', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000112',
        'email'             => 'noverify@example.com',
        'email_verified_at' => null,
        'profile_step'      => ProfileStep::EMAIL_ADDED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/auth/complete-profile', [
        'first_name' => 'Jane',
        'last_name'  => 'Doe',
    ]);

    $response->assertForbidden();
});

it('returns 403 when email is not verified (no phone field in payload)', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000114',
        'email'             => 'notverified@example.com',
        'email_verified_at' => null,
        'profile_step'      => ProfileStep::EMAIL_ADDED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/auth/complete-profile', [
        'first_name' => 'Ghost',
        'last_name'  => 'User',
    ]);

    $response->assertForbidden();
});

it('validates required fields', function (): void {
    $user = User::factory()->verified()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/auth/complete-profile', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['first_name', 'last_name']);
});

it('validates name length constraints', function (): void {
    $user = User::factory()->verified()->create();
    Sanctum::actingAs($user);

    $tooLong = str_repeat('a', 256);

    $response = $this->postJson('/api/v1/auth/complete-profile', [
        'first_name' => $tooLong,
        'last_name'  => $tooLong,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['first_name', 'last_name']);
});

it('keeps step completed when already completed and updates names', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'phone'        => '+37360000113',
        'email'        => 'done@example.com',
        'first_name'   => 'Old',
        'last_name'    => 'Name',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $payload = [
        'first_name' => 'New',
        'last_name'  => 'Name',
    ];

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/complete-profile', $payload)
        ->assertOk()
        ->assertJsonPath('data.profile_step', ProfileStep::COMPLETED->value);

    $user->refresh();

    expect($user->first_name)->toBe('New')
        ->and($user->last_name)->toBe('Name')
        ->and($user->profile_step)->toBe(ProfileStep::COMPLETED);
});

it('returns 401 when unauthenticated', function (): void {
    $response = $this->postJson('/api/v1/auth/complete-profile', []);

    $response->assertUnauthorized();
});
