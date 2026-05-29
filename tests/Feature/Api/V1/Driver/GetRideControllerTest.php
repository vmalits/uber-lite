<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can view their own ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $this->getJson("/api/v1/driver/rides/{$ride->id}")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $ride->id);
});

test('driver cannot view another driver ride', function (): void {
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
        'driver_id' => $otherDriver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $this->getJson("/api/v1/driver/rides/{$ride->id}")
        ->assertForbidden();
});

test('rider cannot access driver ride detail', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->getJson("/api/v1/driver/rides/{$ride->id}")
        ->assertForbidden();
});

test('unauthenticated user cannot view driver ride', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::COMPLETED,
    ]);

    $this->getJson("/api/v1/driver/rides/{$ride->id}")
        ->assertUnauthorized();
});

test('driver can view ride with rating', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride->rating()->create([
        'rider_id' => $ride->rider_id,
        'rating'   => 5,
        'comment'  => 'Great ride!',
    ]);

    Sanctum::actingAs($driver);

    $this->getJson("/api/v1/driver/rides/{$ride->id}")
        ->assertOk()
        ->assertJsonPath('data.rating.rating', 5)
        ->assertJsonPath('data.rating.comment', 'Great ride!');
});
