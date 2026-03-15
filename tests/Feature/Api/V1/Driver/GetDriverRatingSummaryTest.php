<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('driver can get rating summary', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride3 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->forRide($ride1)->create(['rating' => 5]);
    RideRating::factory()->forRide($ride2)->create(['rating' => 5]);
    RideRating::factory()->forRide($ride3)->create(['rating' => 4]);

    actingAs($driver)
        ->getJson('/api/v1/driver/rating-summary')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.average_rating', 4.67)
        ->assertJsonPath('data.total_reviews', 3)
        ->assertJsonPath('data.rating_distribution.5', 2)
        ->assertJsonPath('data.rating_distribution.4', 1)
        ->assertJsonPath('data.rating_distribution.3', 0)
        ->assertJsonPath('data.rating_distribution.2', 0)
        ->assertJsonPath('data.rating_distribution.1', 0);
});

test('driver with no reviews returns zeros', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/rating-summary')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.average_rating', 0)
        ->assertJsonPath('data.total_reviews', 0)
        ->assertJsonPath('data.rating_distribution.5', 0)
        ->assertJsonPath('data.rating_distribution.4', 0)
        ->assertJsonPath('data.rating_distribution.3', 0)
        ->assertJsonPath('data.rating_distribution.2', 0)
        ->assertJsonPath('data.rating_distribution.1', 0);
});

test('unauthenticated user cannot access rating summary', function (): void {
    getJson('/api/v1/driver/rating-summary')
        ->assertStatus(401);
});

test('rider cannot access driver rating summary', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/driver/rating-summary')
        ->assertStatus(403);
});

test('rating summary only includes completed rides', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $completedRide = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $cancelledRide = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::CANCELLED,
    ]);

    RideRating::factory()->forRide($completedRide)->create(['rating' => 5]);
    RideRating::factory()->forRide($cancelledRide)->create(['rating' => 1]);

    actingAs($driver)
        ->getJson('/api/v1/driver/rating-summary')
        ->assertStatus(200)
        ->assertJsonPath('data.total_reviews', 1)
        ->assertJsonPath('data.rating_distribution.5', 1)
        ->assertJsonPath('data.rating_distribution.1', 0);
});
