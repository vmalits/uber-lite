<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can rate rider after completed ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating'  => 4,
        'comment' => 'Polite and friendly rider.',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.driver_rating', 4)
        ->assertJsonPath('data.driver_comment', 'Polite and friendly rider.');

    $this->assertDatabaseHas('ride_ratings', [
        'ride_id'        => $ride->id,
        'driver_rating'  => 4,
        'driver_comment' => 'Polite and friendly rider.',
    ]);
});

test('driver can rate rider when rider rating already exists', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride->id,
        'rider_id' => $ride->rider_id,
        'rating'   => 5,
        'comment'  => 'Great driver!',
    ]);

    Sanctum::actingAs($driver);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating' => 3,
    ])
        ->assertOk()
        ->assertJsonPath('data.driver_rating', 3);

    $this->assertDatabaseHas('ride_ratings', [
        'ride_id'       => $ride->id,
        'rating'        => 5,
        'driver_rating' => 3,
    ]);
});

test('driver cannot rate rider for non-completed ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::STARTED,
    ]);

    Sanctum::actingAs($driver);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating' => 4,
    ])
        ->assertForbidden();
});

test('driver cannot rate rider for another driver ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherDriver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $otherDriver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating' => 4,
    ])
        ->assertForbidden();
});

test('rider cannot access driver rate-rider endpoint', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($rider);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating' => 4,
    ])
        ->assertForbidden();
});

test('unauthenticated user cannot rate rider', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating' => 4,
    ])
        ->assertUnauthorized();
});

test('rate rider validates rating is required', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [])
        ->assertJsonValidationErrors(['rating']);
});

test('rate rider validates rating between 1 and 5', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/rate-rider", [
        'rating' => 6,
    ])
        ->assertJsonValidationErrors(['rating']);
});
