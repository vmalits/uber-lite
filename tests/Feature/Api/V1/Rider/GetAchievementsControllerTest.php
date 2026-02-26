<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Laravel\Sanctum\Sanctum;

test('rider can get their achievements', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement = Achievement::factory()->rider()->create([
        'name'          => 'First Ride',
        'key'           => 'rider_first_ride',
        'target_value'  => 1,
        'points_reward' => 10,
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $rider->id,
        'achievement_id' => $achievement->id,
        'progress'       => 1,
        'completed_at'   => now(),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/achievements');

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

test('rider can filter achievements by completed status', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement1 = Achievement::factory()->rider()->create(['target_value' => 1]);
    $achievement2 = Achievement::factory()->rider()->create(['target_value' => 1]);

    UserAchievement::factory()->create([
        'user_id'        => $rider->id,
        'achievement_id' => $achievement1->id,
        'completed_at'   => now(),
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $rider->id,
        'achievement_id' => $achievement2->id,
        'completed_at'   => null,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/achievements?status=completed');

    $response->assertOk()
        ->assertJsonPath('data.meta.total', 1)
        ->assertJsonPath('data.data.0.is_completed', true);
});

test('rider can filter achievements by in_progress status', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement1 = Achievement::factory()->rider()->create(['target_value' => 1]);
    $achievement2 = Achievement::factory()->rider()->create(['target_value' => 1]);

    UserAchievement::factory()->create([
        'user_id'        => $rider->id,
        'achievement_id' => $achievement1->id,
        'completed_at'   => now(),
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $rider->id,
        'achievement_id' => $achievement2->id,
        'completed_at'   => null,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/achievements?status=in_progress');

    $response->assertOk()
        ->assertJsonPath('data.meta.total', 1)
        ->assertJsonPath('data.data.0.is_completed', false);
});

test('rider with no achievements gets empty list', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/achievements');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.meta.total', 0)
        ->assertJsonPath('data.data', []);
});

test('guest cannot get rider achievements', function (): void {
    $response = $this->getJson('/api/v1/rider/achievements');

    $response->assertUnauthorized();
});

test('driver cannot get rider achievements', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/achievements');

    $response->assertForbidden();
});

test('rider can paginate achievements', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievements = Achievement::factory()->rider()->count(20)->create();

    foreach ($achievements as $achievement) {
        UserAchievement::factory()->create([
            'user_id'        => $rider->id,
            'achievement_id' => $achievement->id,
        ]);
    }

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/achievements?per_page=5');

    $response->assertOk()
        ->assertJsonPath('data.meta.per_page', 5)
        ->assertJsonPath('data.meta.total', 20)
        ->assertJsonPath('data.meta.last_page', 4);
});

test('achievement data includes progress percentage', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $achievement = Achievement::factory()->rider()->create([
        'name'          => 'Ten Rides',
        'key'           => 'rider_10_rides',
        'target_value'  => 10,
        'points_reward' => 50,
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $rider->id,
        'achievement_id' => $achievement->id,
        'progress'       => 7,
        'completed_at'   => null,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/rider/achievements');

    $response->assertOk()
        ->assertJsonPath('data.data.0.achievement.name', 'Ten Rides')
        ->assertJsonPath('data.data.0.progress', 7)
        ->assertJsonPath('data.data.0.progress_percentage', 70)
        ->assertJsonPath('data.data.0.is_completed', false);
});
