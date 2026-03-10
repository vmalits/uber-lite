<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideStop;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('rider can add stop to pending ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => '123 Main St, City',
        'lat'     => 40.7128,
        'lng'     => -74.0060,
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'order',
                'address',
                'lat',
                'lng',
                'created_at',
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.order', 1)
        ->assertJsonPath('data.address', '123 Main St, City');

    $this->assertDatabaseHas('ride_stops', [
        'ride_id' => $ride->id,
        'order'   => 1,
        'address' => '123 Main St, City',
    ]);
});

test('rider can add stop to accepted ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.order', 1);
});

test('rider can add stop to on the way ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertCreated();
});

test('rider cannot add stop to ride in progress', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::STARTED,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertForbidden();
});

test('rider cannot add stop to completed ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertForbidden();
});

test('rider can add multiple stops with correct order', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->rider);

    $response1 = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'First Stop',
    ]);
    $response1->assertCreated()->assertJsonPath('data.order', 1);

    $response2 = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Second Stop',
    ]);
    $response2->assertCreated()->assertJsonPath('data.order', 2);
});

test('rider cannot add more than three stops', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    RideStop::factory()->forRide($ride, 1)->create();
    RideStop::factory()->forRide($ride, 2)->create();
    RideStop::factory()->forRide($ride, 3)->create();

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Fourth Stop',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['stop']);
});

test('rider cannot add stop to another riders ride', function (): void {
    $anotherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $anotherRider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertForbidden();
});

test('guest cannot add stop', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertUnauthorized();
});

test('driver cannot add stop to rider endpoint', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
    ]);

    $response->assertForbidden();
});

test('address is required', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['address']);
});

test('lng is required when lat is provided', function (): void {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/stops", [
        'address' => 'Stop Address',
        'lat'     => 40.7128,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['lng']);
});

test('non-existent ride returns 404', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->postJson('/api/v1/rider/rides/non-existent-id/stops', [
        'address' => 'Stop Address',
    ]);

    $response->assertNotFound();
});
