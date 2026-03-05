<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can get driver leaderboard sorted by rating', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver1 = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'first_name'   => 'John',
        'last_name'    => 'Doe',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $driver2 = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'first_name'   => 'Jane',
        'last_name'    => 'Smith',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $riderForRide = User::factory()->create(['role' => UserRole::RIDER]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $driver1->id,
        'rider_id'  => $riderForRide->id,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $driver2->id,
        'rider_id'  => $riderForRide->id,
    ]);

    RideRating::factory()->create([
        'ride_id' => $ride1->id,
        'rating'  => 5.0,
    ]);

    RideRating::factory()->create([
        'ride_id' => $ride2->id,
        'rating'  => 4.0,
    ]);

    actingAs($rider)
        ->getJson(route('api.v1.leaderboard.drivers'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'avatar_paths',
                    'rating',
                    'total_rides',
                    'rank',
                ],
            ],
        ]);
});

test('can get driver leaderboard sorted by rides', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver1 = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $driver2 = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $riderForRide = User::factory()->create(['role' => UserRole::RIDER]);

    Ride::factory()->count(5)->create([
        'driver_id' => $driver1->id,
        'rider_id'  => $riderForRide->id,
        'status'    => 'completed',
    ]);

    Ride::factory()->count(3)->create([
        'driver_id' => $driver2->id,
        'rider_id'  => $riderForRide->id,
        'status'    => 'completed',
    ]);

    actingAs($rider)
        ->getJson(route('api.v1.leaderboard.drivers').'?sort=-total_rides')
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('leaderboard respects limit parameter', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    User::factory()->count(15)->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    actingAs($rider)
        ->getJson(route('api.v1.leaderboard.drivers').'?limit=5')
        ->assertStatus(200)
        ->assertJsonCount(5, 'data');
});

test('banned drivers are excluded from leaderboard', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $activeDriver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $bannedDriver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
        'banned_at'    => now(),
    ]);

    actingAs($rider)
        ->getJson(route('api.v1.leaderboard.drivers'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $activeDriver->id);
});

test('unauthenticated user cannot access leaderboard', function (): void {
    getJson(route('api.v1.leaderboard.drivers'))
        ->assertStatus(401);
});

test('leaderboard returns drivers in correct rank order', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver1 = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'first_name'   => 'Top',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $driver2 = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'first_name'   => 'Second',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $riderForRide = User::factory()->create(['role' => UserRole::RIDER]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $driver1->id,
        'rider_id'  => $riderForRide->id,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $driver2->id,
        'rider_id'  => $riderForRide->id,
    ]);

    RideRating::factory()->create([
        'ride_id' => $ride1->id,
        'rating'  => 5.0,
    ]);

    RideRating::factory()->create([
        'ride_id' => $ride2->id,
        'rating'  => 3.0,
    ]);

    $response = actingAs($rider)
        ->getJson(route('api.v1.leaderboard.drivers'))
        ->assertStatus(200);

    $data = $response->json('data');

    expect($data[0]['rank'])->toBe(1)
        ->and($data[0]['first_name'])->toBe('Top')
        ->and($data[1]['rank'])->toBe(2)
        ->and($data[1]['first_name'])->toBe('Second');
});
