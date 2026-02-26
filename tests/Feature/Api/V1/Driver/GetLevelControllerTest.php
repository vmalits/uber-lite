<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserTier;
use App\Models\User;
use App\Models\UserLevel;
use Laravel\Sanctum\Sanctum;

test('driver can get their level info', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    UserLevel::factory()->create([
        'user_id' => $driver->id,
        'level'   => 5,
        'xp'      => 520,
        'tier'    => UserTier::SILVER,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/level');

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
        ->assertJsonPath('data.level', 5)
        ->assertJsonPath('data.xp', 520)
        ->assertJsonPath('data.tier', 'silver');
});

test('driver gets default level when no level record exists', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/level');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.level', 1)
        ->assertJsonPath('data.xp', 0)
        ->assertJsonPath('data.tier', 'bronze')
        ->assertJsonPath('data.xp_to_next_tier', 500)
        ->assertJsonPath('data.next_tier', 'silver');
});

test('guest cannot get driver level', function (): void {
    $response = $this->getJson('/api/v1/driver/level');

    $response->assertUnauthorized();
});

test('rider cannot get driver level', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/level');

    $response->assertForbidden();
});

test('driver with incomplete profile cannot get level', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/level');

    $response->assertForbidden();
});

test('platinum driver sees null for next tier', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    UserLevel::factory()->create([
        'user_id' => $driver->id,
        'level'   => 60,
        'xp'      => 7000,
        'tier'    => UserTier::PLATINUM,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/level');

    $response->assertOk()
        ->assertJsonPath('data.tier', 'platinum')
        ->assertJsonPath('data.xp_to_next_tier', null)
        ->assertJsonPath('data.next_tier', null);
});
