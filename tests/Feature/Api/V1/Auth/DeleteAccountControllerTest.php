<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

it('deletes the authenticated account and revokes all tokens', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37360000999',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'email'             => 'gdpr@example.com',
        'email_verified_at' => null,
    ]);

    // Issue a token and ensure it exists
    Sanctum::actingAs($user);
    $token = $user->createToken('auth')->plainTextToken;

    // Sanity check token exists
    expect(PersonalAccessToken::findToken(Str::after($token, '|')))->not->toBeNull();

    $this->deleteJson('/api/v1/auth/delete-account')
        ->assertOk()
        ->assertJson([
            'message' => 'Account deleted successfully.',
        ]);

    // User should be removed
    $this->assertDatabaseMissing('users', ['id' => $user->id]);

    // Token should be revoked
    expect(PersonalAccessToken::findToken(Str::after($token, '|')))->toBeNull();
});

it('returns 401 when unauthenticated', function (): void {
    $this->deleteJson('/api/v1/auth/delete-account')
        ->assertUnauthorized();
});
