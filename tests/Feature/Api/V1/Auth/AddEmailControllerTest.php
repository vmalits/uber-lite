<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

it('adds email for a user with verified phone', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000000',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'email'             => null,
        'email_verified_at' => null,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'email' => 'user@gmail.com',
    ];

    $response = $this->postJson('/api/v1/auth/add-email', $payload);

    $response->assertOk()
        ->assertJson([
            'message' => 'Email added successfully.',
            'data'    => [
                'email'        => 'user@gmail.com',
                'profile_step' => ProfileStep::EMAIL_ADDED->value,
            ],
        ]);

    $user->refresh();

    expect($user->email)->toBe('user@gmail.com')
        ->and($user->email_verified_at)->toBeNull()
        ->and($user->profile_step)->toBe(ProfileStep::EMAIL_ADDED);
});

it('sends verification email with signed link after adding email', function (): void {
    Notification::fake();

    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000088',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'email'             => null,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'email' => 'notify@gmail.com',
    ];

    $this->postJson('/api/v1/auth/add-email', $payload)->assertOk();

    Notification::assertSentTo(
        $user,
        VerifyEmailNotification::class,
        function (VerifyEmailNotification $notification) use ($user) {
            return $user->id !== '';
        },
    );
});

it('returns 422 when phone is not verified', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000001',
        'phone_verified_at' => null,
        'profile_step'      => null,
        'email'             => null,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/auth/add-email', [
        'email' => 'another@gmail.com',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

it('returns 401 when unauthenticated', function (): void {
    $response = $this->postJson('/api/v1/auth/add-email', [
        'email' => 'ghost@gmail.com',
    ]);

    $response->assertUnauthorized();
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

    Sanctum::actingAs($verified);

    $response = $this->postJson('/api/v1/auth/add-email', [
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

    Sanctum::actingAs($verified);

    $response = $this->postJson('/api/v1/auth/add-email', [
        'email' => 'not-an-email',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});
