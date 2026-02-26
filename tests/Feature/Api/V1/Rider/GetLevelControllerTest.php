<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserTier;
use App\Models\User;
use App\Models\UserLevel;
use Laravel\Sanctum\Sanctum;

test('rider can get their level info', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    UserLevel::factory()->create([
        'user_id' => $rider->id,
        'level'   => 3,
        'xp'      => 250,
        'tier'    => UserTier::BRONZE,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/level');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'level',
                'xp',
                'tier',
                'xp_to_next_tier',
                'next_tier',
                'tier_threshold',
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.level', 3)
        ->assertJsonPath('data.xp', 250)
        ->assertJsonPath('data.tier', 'bronze');
});

test('rider gets default level when no level record exists', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/level');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.level', 1)
        ->assertJsonPath('data.xp', 0)
        ->assertJsonPath('data.tier', 'bronze')
        ->assertJsonPath('data.xp_to_next_tier', 500)
        ->assertJsonPath('data.next_tier', 'silver');
});

test('guest cannot get rider level', function (): void {
    $response = $this->getJson('/api/v1/rider/level');

    $response->assertUnauthorized();
});

test('driver cannot get rider level', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/level');

    $response->assertForbidden();
});

test('rider with incomplete profile cannot get level', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/level');

    $response->assertForbidden();
});

test('rider with silver tier sees correct data', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    UserLevel::factory()->create([
        'user_id' => $rider->id,
        'level'   => 8,
        'xp'      => 750,
        'tier'    => UserTier::SILVER,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/level');

    $response->assertOk()
        ->assertJsonPath('data.tier', 'silver')
        ->assertJsonPath('data.xp_to_next_tier', 1250)
        ->assertJsonPath('data.next_tier', 'gold')
        ->assertJsonPath('data.tier_threshold', 500);
});
