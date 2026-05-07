<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideStop;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can delete a stop from their ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $stop = RideStop::factory()->forRide($ride, 1)->create();

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/rides/{$ride->id}/stops/{$stop->id}")
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('ride_stops', [
        'id' => $stop->id,
    ]);
});

test('deleting a stop reorders remaining stops', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $stop1 = RideStop::factory()->forRide($ride, 1)->create();
    $stop2 = RideStop::factory()->forRide($ride, 2)->create();
    $stop3 = RideStop::factory()->forRide($ride, 3)->create();

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/rides/{$ride->id}/stops/{$stop2->id}")
        ->assertOk();

    $this->assertDatabaseHas('ride_stops', [
        'id'    => $stop1->id,
        'order' => 1,
    ]);

    $this->assertDatabaseHas('ride_stops', [
        'id'    => $stop3->id,
        'order' => 2,
    ]);

    $this->assertDatabaseMissing('ride_stops', [
        'id' => $stop2->id,
    ]);
});

test('rider cannot delete stop from another rider ride', function (): void {
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
        'rider_id' => $otherRider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $stop = RideStop::factory()->forRide($ride, 1)->create();

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/rides/{$ride->id}/stops/{$stop->id}")
        ->assertForbidden();
});

test('rider cannot delete stop from started ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::STARTED,
    ]);

    $stop = RideStop::factory()->forRide($ride, 1)->create();

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/rides/{$ride->id}/stops/{$stop->id}")
        ->assertForbidden();
});

test('rider cannot delete stop that belongs to different ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    /** @var Ride $otherRide */
    $otherRide = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $stop = RideStop::factory()->forRide($otherRide, 1)->create();

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/rides/{$ride->id}/stops/{$stop->id}")
        ->assertForbidden();
});

test('unauthenticated user cannot delete ride stop', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::PENDING,
    ]);

    $stop = RideStop::factory()->forRide($ride, 1)->create();

    $this->deleteJson("/api/v1/rider/rides/{$ride->id}/stops/{$stop->id}")
        ->assertUnauthorized();
});
