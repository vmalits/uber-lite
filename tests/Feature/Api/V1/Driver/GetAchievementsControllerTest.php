<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Laravel\Sanctum\Sanctum;

test('driver can get their achievements', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement = Achievement::factory()->driver()->create([
        'name'          => 'First Ride',
        'key'           => 'driver_first_ride',
        'target_value'  => 1,
        'points_reward' => 10,
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $driver->id,
        'achievement_id' => $achievement->id,
        'progress'       => 1,
        'completed_at'   => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/achievements');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.meta.total', 1);
});

test('driver can filter achievements by completed status', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement1 = Achievement::factory()->driver()->create(['target_value' => 1]);
    $achievement2 = Achievement::factory()->driver()->create(['target_value' => 1]);

    UserAchievement::factory()->create([
        'user_id'        => $driver->id,
        'achievement_id' => $achievement1->id,
        'completed_at'   => now(),
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $driver->id,
        'achievement_id' => $achievement2->id,
        'completed_at'   => null,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/achievements?status=completed');

    $response->assertOk()
        ->assertJsonPath('data.meta.total', 1)
        ->assertJsonPath('data.data.0.is_completed', true);
});

test('driver can filter achievements by in_progress status', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement1 = Achievement::factory()->driver()->create(['target_value' => 1]);
    $achievement2 = Achievement::factory()->driver()->create(['target_value' => 1]);

    UserAchievement::factory()->create([
        'user_id'        => $driver->id,
        'achievement_id' => $achievement1->id,
        'completed_at'   => now(),
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $driver->id,
        'achievement_id' => $achievement2->id,
        'completed_at'   => null,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/achievements?status=in_progress');

    $response->assertOk()
        ->assertJsonPath('data.meta.total', 1)
        ->assertJsonPath('data.data.0.is_completed', false);
});

test('driver with no achievements gets empty list', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/achievements');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.meta.total', 0)
        ->assertJsonPath('data.data', []);
});

test('guest cannot get driver achievements', function (): void {
    $response = $this->getJson('/api/v1/driver/achievements');

    $response->assertUnauthorized();
});

test('rider cannot get driver achievements', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/achievements');

    $response->assertForbidden();
});

test('driver can paginate achievements', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievements = Achievement::factory()->driver()->count(20)->create();

    foreach ($achievements as $achievement) {
        UserAchievement::factory()->create([
            'user_id'        => $driver->id,
            'achievement_id' => $achievement->id,
        ]);
    }

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/achievements?per_page=5');

    $response->assertOk()
        ->assertJsonPath('data.meta.per_page', 5)
        ->assertJsonPath('data.meta.total', 20)
        ->assertJsonPath('data.meta.last_page', 4);
});

test('achievement data includes correct fields', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement = Achievement::factory()->driver()->create([
        'name'          => 'First Ride',
        'key'           => 'driver_first_ride',
        'description'   => 'Complete your first ride',
        'icon'          => 'trophy',
        'target_value'  => 1,
        'points_reward' => 10,
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $driver->id,
        'achievement_id' => $achievement->id,
        'progress'       => 1,
        'completed_at'   => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/achievements');

    $response->assertOk()
        ->assertJsonPath('data.data.0.achievement.name', 'First Ride')
        ->assertJsonPath('data.data.0.achievement.key', 'driver_first_ride')
        ->assertJsonPath('data.data.0.achievement.target_value', 1)
        ->assertJsonPath('data.data.0.achievement.points_reward', 10)
        ->assertJsonPath('data.data.0.progress', 1)
        ->assertJsonPath('data.data.0.progress_percentage', 100)
        ->assertJsonPath('data.data.0.is_completed', true);
});
