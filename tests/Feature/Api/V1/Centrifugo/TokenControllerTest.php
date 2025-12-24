<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('authenticated user with completed profile can get centrifugo token', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/ws/token');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'token',
            ],
        ]);

    $token = $response->json('data.token');
    $parts = explode('.', $token);

    expect($parts)->toHaveCount(3);

    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

    expect($payload['sub'])->toBe($user->id);
});

test('authenticated user without completed profile cannot get centrifugo token', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/ws/token');

    $response->assertForbidden();
});

test('unauthenticated user cannot get centrifugo token', function (): void {
    $response = $this->getJson('/api/v1/ws/token');

    $response->assertUnauthorized();
});
