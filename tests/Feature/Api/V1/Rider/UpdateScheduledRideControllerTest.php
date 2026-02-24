<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can update scheduled ride time', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $originalTime = now()->addHours(2);
    $newTime = now()->addHours(5);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'               => $rider->id,
        'status'                 => RideStatus::SCHEDULED,
        'scheduled_at'           => $originalTime,
        'origin_address'         => 'Original Address',
        'origin_lat'             => 47.0105,
        'origin_lng'             => 28.8638,
        'destination_address'    => 'Original Destination',
        'destination_lat'        => 47.0225,
        'destination_lng'        => 28.8353,
        'estimated_price'        => 100,
        'estimated_distance_km'  => 5.0,
        'estimated_duration_min' => 10.0,
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'scheduled_at' => $newTime->toDateTimeString(),
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Scheduled ride updated successfully.');

    $this->assertDatabaseHas('rides', [
        'id'           => $ride->id,
        'scheduled_at' => $newTime->toDateTimeString(),
    ]);
});

test('rider can update scheduled ride origin', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'            => $rider->id,
        'status'              => RideStatus::SCHEDULED,
        'scheduled_at'        => now()->addHours(2),
        'origin_address'      => 'Old Origin',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'Destination',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'origin_address' => 'New Origin Address',
        'origin_lat'     => 47.0200,
        'origin_lng'     => 28.8700,
    ])
        ->assertOk()
        ->assertJsonPath('data.origin_address', 'New Origin Address')
        ->assertJsonPath('data.origin_lat', 47.0200);

    $this->assertDatabaseHas('rides', [
        'id'             => $ride->id,
        'origin_address' => 'New Origin Address',
        'origin_lat'     => 47.0200,
        'origin_lng'     => 28.8700,
    ]);
});

test('rider can update scheduled ride destination', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'            => $rider->id,
        'status'              => RideStatus::SCHEDULED,
        'scheduled_at'        => now()->addHours(2),
        'origin_address'      => 'Origin',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'Old Destination',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'destination_address' => 'New Destination Address',
        'destination_lat'     => 47.0300,
        'destination_lng'     => 28.8400,
    ])
        ->assertOk()
        ->assertJsonPath('data.destination_address', 'New Destination Address');

    $this->assertDatabaseHas('rides', [
        'id'                  => $ride->id,
        'destination_address' => 'New Destination Address',
        'destination_lat'     => 47.0300,
        'destination_lng'     => 28.8400,
    ]);
});

test('rider can update all fields at once', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'            => $rider->id,
        'status'              => RideStatus::SCHEDULED,
        'scheduled_at'        => now()->addHours(2),
        'origin_address'      => 'Old Origin',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'Old Destination',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ]);

    Sanctum::actingAs($rider);

    $newTime = now()->addHours(4);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'origin_address'      => 'New Origin',
        'origin_lat'          => 47.0150,
        'origin_lng'          => 28.8650,
        'destination_address' => 'New Destination',
        'destination_lat'     => 47.0250,
        'destination_lng'     => 28.8400,
        'scheduled_at'        => $newTime->toDateTimeString(),
    ])
        ->assertOk()
        ->assertJsonPath('data.origin_address', 'New Origin')
        ->assertJsonPath('data.destination_address', 'New Destination');
});

test('rider cannot update non-scheduled ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'scheduled_at' => now()->addHours(3)->toDateTimeString(),
    ])
        ->assertForbidden();
});

test('rider cannot update another rider scheduled ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $otherRider->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'scheduled_at' => now()->addHours(3)->toDateTimeString(),
    ])
        ->assertForbidden();
});

test('scheduled_at must be in the future', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'scheduled_at' => now()->subHour()->toDateTimeString(),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['scheduled_at']);
});

test('origin requires coordinates', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'origin_address' => 'New Address',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['origin_lat', 'origin_lng']);
});

test('destination requires coordinates', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'destination_address' => 'New Destination',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['destination_lat', 'destination_lng']);
});

test('unauthenticated user cannot update scheduled ride', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    $this->putJson("/api/v1/rider/rides/scheduled/{$ride->id}", [
        'scheduled_at' => now()->addHours(3)->toDateTimeString(),
    ])
        ->assertUnauthorized();
});
