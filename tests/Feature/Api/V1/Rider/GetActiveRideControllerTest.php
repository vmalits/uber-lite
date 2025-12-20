<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can get their active ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/active');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => [
                'id'     => $ride->id,
                'status' => RideStatus::ACCEPTED->value,
            ],
        ]);
});

test('rider gets message when no active ride exists', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create a completed ride (not active)
    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/active');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'No active ride found.',
        ])->assertJsonMissing(['data']);
});

test('rider gets the latest active ride if multiple exist', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $oldRide */
    $oldRide = Ride::factory()->create([
        'rider_id'   => $user->id,
        'status'     => RideStatus::PENDING,
        'created_at' => now()->subHour(),
    ]);

    /** @var Ride $newRide */
    $newRide = Ride::factory()->create([
        'rider_id'   => $user->id,
        'status'     => RideStatus::ACCEPTED,
        'created_at' => now(),
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/active');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => [
                'id'     => $newRide->id,
                'status' => RideStatus::ACCEPTED->value,
            ],
        ]);
});

test('guest cannot get active ride', function (): void {
    $response = $this->getJson('/api/v1/rider/rides/active');

    $response->assertUnauthorized();
});

test('rider with incomplete profile cannot get active ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/active');

    $response->assertForbidden();
});
