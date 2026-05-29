<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can cancel scheduled ride', function (): void {
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

    $this->deleteJson("/api/v1/rider/rides/scheduled/{$ride->id}")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Scheduled ride cancelled successfully.');

    $this->assertDatabaseHas('rides', [
        'id'     => $ride->id,
        'status' => RideStatus::CANCELLED->value,
    ]);

    expect($ride->refresh()->cancelled_at)->not->toBeNull();
});

test('rider cannot cancel non-scheduled ride', function (): void {
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

    $this->deleteJson("/api/v1/rider/rides/scheduled/{$ride->id}")
        ->assertForbidden();
});

test('rider cannot cancel another rider scheduled ride', function (): void {
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

    $this->deleteJson("/api/v1/rider/rides/scheduled/{$ride->id}")
        ->assertForbidden();
});

test('unauthenticated user cannot cancel scheduled ride', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    $this->deleteJson("/api/v1/rider/rides/scheduled/{$ride->id}")
        ->assertUnauthorized();
});

test('cancelled ride is not listed in scheduled rides', function (): void {
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

    $this->deleteJson("/api/v1/rider/rides/scheduled/{$ride->id}")
        ->assertOk();

    $this->getJson('/api/v1/rider/rides/scheduled')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});
