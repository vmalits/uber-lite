<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can add note to pending ride', function (): void {
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

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Please ring the doorbell twice.',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.rider_note', 'Please ring the doorbell twice.');

    $this->assertDatabaseHas('rides', [
        'id'         => $ride->id,
        'rider_note' => 'Please ring the doorbell twice.',
    ]);
});

test('rider can add note to scheduled ride', function (): void {
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

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Blue house with white gate.',
    ])
        ->assertOk()
        ->assertJsonPath('data.rider_note', 'Blue house with white gate.');
});

test('rider can update existing note', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'   => $rider->id,
        'status'     => RideStatus::PENDING,
        'rider_note' => 'Old note',
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Updated note',
    ])
        ->assertOk()
        ->assertJsonPath('data.rider_note', 'Updated note');
});

test('rider can clear note by sending null', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'   => $rider->id,
        'status'     => RideStatus::PENDING,
        'rider_note' => 'Existing note',
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => null,
    ])
        ->assertOk()
        ->assertJsonPath('data.rider_note', null);

    $this->assertDatabaseHas('rides', [
        'id'         => $ride->id,
        'rider_note' => null,
    ]);
});

test('rider cannot add note to accepted ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Too late now.',
    ])
        ->assertForbidden();
});

test('rider cannot add note to completed ride', function (): void {
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

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Too late now.',
    ])
        ->assertForbidden();
});

test('rider cannot add note to another rider ride', function (): void {
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

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Hacking attempt.',
    ])
        ->assertForbidden();
});

test('note is validated to max 500 characters', function (): void {
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

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => str_repeat('a', 501),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['note']);
});

test('unauthenticated user cannot update note', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::PENDING,
    ]);

    $this->putJson("/api/v1/rider/rides/{$ride->id}/note", [
        'note' => 'Anonymous note.',
    ])
        ->assertUnauthorized();
});
