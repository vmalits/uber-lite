<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

it('logs out and revokes the current token', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    $token = $user->createToken('auth')->plainTextToken;

    // Logout with the issued token
    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/auth/logout');

    $response->assertOk()
        ->assertJson([
            'message' => __('messages.auth.logged_out'),
        ]);

    // Ensure the token has been revoked (no longer present in storage)
    expect(PersonalAccessToken::findToken($token))->toBeNull();
});

it('returns 401 when unauthenticated', function (): void {
    $this->postJson('/api/v1/auth/logout')
        ->assertUnauthorized();
});
