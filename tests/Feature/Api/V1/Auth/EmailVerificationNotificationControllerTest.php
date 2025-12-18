<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

it('sends verification notification when email is set and not verified', function (): void {
    Notification::fake();

    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000042',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'email'             => 'verify_me@gmail.com',
        'email_verified_at' => null,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/email/verification-notification')
        ->assertOk()
        ->assertJson([
            'message' => 'Verification link sent.',
        ]);

    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

it('returns 422 when email is not set', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000043',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'email'             => null,
        'email_verified_at' => null,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/email/verification-notification')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('returns ok message when email already verified and does not send notification', function (): void {
    Notification::fake();

    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000044',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::EMAIL_ADDED,
        'email'             => 'already@verified.com',
        'email_verified_at' => now(),
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/email/verification-notification')
        ->assertOk()
        ->assertJson([
            'message' => 'Email is already verified.',
        ]);

    Notification::assertNothingSent();
});

it('returns 401 when unauthenticated', function (): void {
    $this->postJson('/api/v1/auth/email/verification-notification')
        ->assertUnauthorized();
});
