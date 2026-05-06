<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideTip;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can view receipt for their completed ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 150,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $ride->id)
        ->assertJsonPath('data.ride_earnings', 150)
        ->assertJsonPath('data.status', 'completed');
});

test('driver cannot view receipt for non-completed ride', function (): void {
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

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertForbidden();
});

test('driver cannot view receipt for another driver ride', function (): void {
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

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertForbidden();
});

test('rider cannot access driver receipt endpoint', function (): void {
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

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertForbidden();
});

test('unauthenticated user cannot view driver receipt', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertUnauthorized();
});

test('driver receipt includes tip when present', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 200,
        'completed_at' => now(),
    ]);

    RideTip::factory()->create([
        'ride_id'   => $ride->id,
        'rider_id'  => $ride->rider_id,
        'driver_id' => $driver->id,
        'amount'    => 50,
    ]);

    Sanctum::actingAs($driver);

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertOk()
        ->assertJsonPath('data.tip.amount', 50);
});

test('driver receipt includes rider info', function (): void {
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

    $this->getJson("/api/v1/driver/rides/{$ride->id}/receipt")
        ->assertOk()
        ->assertJsonPath('data.rider_first_name', $ride->rider->first_name);
});
