<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use App\Models\UserRideStreak;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can get rider leaderboard sorted by streak', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $rider1 = User::factory()->create([
        'role'         => UserRole::RIDER,
        'first_name'   => 'John',
        'last_name'    => 'Doe',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $rider2 = User::factory()->create([
        'role'         => UserRole::RIDER,
        'first_name'   => 'Jane',
        'last_name'    => 'Smith',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    UserRideStreak::factory()->create([
        'user_id'        => $rider1->id,
        'current_streak' => 15,
    ]);

    UserRideStreak::factory()->create([
        'user_id'        => $rider2->id,
        'current_streak' => 10,
    ]);

    actingAs($driver)
        ->getJson(route('api.v1.leaderboard.riders'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'avatar_paths',
                    'current_streak',
                    'total_rides',
                    'rank',
                ],
            ],
        ]);
});

test('can get rider leaderboard sorted by rides', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $rider1 = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $rider2 = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->count(5)->create([
        'rider_id'  => $rider1->id,
        'driver_id' => $driver->id,
        'status'    => 'completed',
    ]);

    Ride::factory()->count(3)->create([
        'rider_id'  => $rider2->id,
        'driver_id' => $driver->id,
        'status'    => 'completed',
    ]);

    actingAs($driver)
        ->getJson(route('api.v1.leaderboard.riders').'?sort=-total_rides')
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('leaderboard respects limit parameter', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    User::factory()->count(15)->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    actingAs($driver)
        ->getJson(route('api.v1.leaderboard.riders').'?limit=5')
        ->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

test('banned riders are excluded from leaderboard', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $activeRider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $bannedRider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
        'banned_at'    => now(),
    ]);

    actingAs($driver)
        ->getJson(route('api.v1.leaderboard.riders'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $activeRider->id);
});

test('unauthenticated user cannot access leaderboard', function (): void {
    getJson(route('api.v1.leaderboard.riders'))
        ->assertStatus(401);
});

test('leaderboard returns riders in correct rank order by streak', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $rider1 = User::factory()->create([
        'role'         => UserRole::RIDER,
        'first_name'   => 'Top',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $rider2 = User::factory()->create([
        'role'         => UserRole::RIDER,
        'first_name'   => 'Second',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    UserRideStreak::factory()->create([
        'user_id'        => $rider1->id,
        'current_streak' => 20,
    ]);

    UserRideStreak::factory()->create([
        'user_id'        => $rider2->id,
        'current_streak' => 10,
    ]);

    $response = actingAs($driver)
        ->getJson(route('api.v1.leaderboard.riders'))
        ->assertStatus(200);

    $data = $response->json('data');

    expect($data[0]['rank'])->toBe(1)
        ->and($data[0]['first_name'])->toBe('Top')
        ->and($data[0]['current_streak'])->toBe(20)
        ->and($data[1]['rank'])->toBe(2)
        ->and($data[1]['first_name'])->toBe('Second')
        ->and($data[1]['current_streak'])->toBe(10);
});
