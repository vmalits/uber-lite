<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Support\Facades\URL;

it('verifies email using signed link and updates profile step', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000099',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::EMAIL_ADDED,
        'email'             => 'verifyme@example.com',
        'email_verified_at' => null,
    ]);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'user' => $user,
            'hash' => sha1('verifyme@example.com'),
        ],
    );

    $response = $this->actingAs($user)
        ->getJson(
            parse_url($url, PHP_URL_PATH).'?'.parse_url($url, PHP_URL_QUERY));

    $response->assertOk()
        ->assertJson([
            'message' => 'Email verified successfully.',
            'data'    => [
                'user_id'      => $user->id,
                'email'        => 'verifyme@example.com',
                'profile_step' => ProfileStep::EMAIL_VERIFIED->value,
            ],
        ]);

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull()
        ->and($user->profile_step)->toBe(ProfileStep::EMAIL_VERIFIED);
});

it('rejects invalid hash', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'email' => 'bad@example.com',
    ]);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'user' => $user,
            'hash' => sha1('wrong@example.com'),
        ],
    );

    $response = $this->actingAs($user)->getJson(parse_url($url, PHP_URL_PATH).'?'.parse_url($url, PHP_URL_QUERY));
    $response->assertForbidden();
});
