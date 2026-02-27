<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('rider can verify PIN', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ACCEPTED,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', __('messages.ride.pin_verified'));

    expect($ride->fresh()->isPinVerified())->toBeTrue();
});

test('rider cannot verify PIN twice', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'        => $this->rider->id,
        'driver_id'       => $this->driver->id,
        'status'          => RideStatus::ACCEPTED,
        'ride_pin'        => '1234',
        'pin_verified_at' => now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertStatus(400)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', __('messages.ride.pin_already_verified'));
});

test('rider can verify PIN when ride is on the way', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ON_THE_WAY,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertOk();
});

test('rider can verify PIN when ride has arrived', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ARRIVED,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertOk();
});

test('rider cannot verify PIN on pending ride without driver', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => null,
        'status'    => RideStatus::PENDING,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertForbidden();
});

test('rider cannot verify PIN on started ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::STARTED,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertForbidden();
});

test('driver cannot verify rider PIN', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ACCEPTED,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertForbidden();
});

test('unauthorized user cannot verify PIN', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ACCEPTED,
        'ride_pin'  => '1234',
    ]);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertUnauthorized();
});

test('rider cannot verify PIN on another riders ride', function (): void {
    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $otherRider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ACCEPTED,
        'ride_pin'  => '1234',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/verify-pin");

    $response->assertForbidden();
});
