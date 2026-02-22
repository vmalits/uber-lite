<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns empty list when no scheduled rides exist', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/scheduled');

    $response->assertOk()
        ->assertJsonPath('data.items', [])
        ->assertJsonPath('data.pagination.total', 0);
});

it('returns only scheduled rides for the authenticated rider', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherUser = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Scheduled ride for the user
    Ride::factory()->create([
        'rider_id'     => $user->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    // Active ride for the user (should NOT be in the list)
    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
    ]);

    // Scheduled ride for another user (should NOT be in the list)
    Ride::factory()->create([
        'rider_id'     => $otherUser->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(3),
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/scheduled');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.status', RideStatus::SCHEDULED->value);
});

it('sorts scheduled rides by scheduled_at ascending by default', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride1 = Ride::factory()->create([
        'rider_id'     => $user->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(5),
    ]);

    $ride2 = Ride::factory()->create([
        'rider_id'     => $user->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/scheduled');

    $response->assertOk()
        ->assertJsonPath('data.items.0.id', $ride2->id)
        ->assertJsonPath('data.items.1.id', $ride1->id);
});

it('denies access to scheduled rides for non-riders', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/scheduled');

    $response->assertForbidden();
});

it('denies access to scheduled rides for unauthenticated users', function () {
    $response = $this->getJson('/api/v1/rider/rides/scheduled');

    $response->assertUnauthorized();
});
