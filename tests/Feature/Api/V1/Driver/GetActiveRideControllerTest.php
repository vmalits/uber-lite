<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can get active ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $activeRide */
    $activeRide = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    // Create another ride that's not active
    Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/active');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'rider_id',
                'driver_id',
                'origin_address',
                'origin_lat',
                'origin_lng',
                'destination_address',
                'destination_lat',
                'destination_lng',
                'status',
                'price',
                'created_at' => [
                    'human',
                    'string',
                ],
                'updated_at' => [
                    'human',
                    'string',
                ],
            ],
        ])
        ->assertJsonPath('data.id', $activeRide->id)
        ->assertJsonPath('data.status', 'accepted');
});

it('driver gets no active ride message when no active ride exists', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create a completed ride
    Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/active');

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => null,
            'message' => 'No active ride found.',
        ]);
});

it('driver can get active ride that has started', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $activeRide */
    $activeRide = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::STARTED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/active');

    $response->assertOk()
        ->assertJsonPath('data.id', $activeRide->id)
        ->assertJsonPath('data.status', 'started');
});

it('guest cannot get active ride', function (): void {
    $response = $this->getJson('/api/v1/driver/rides/active');
    $response->assertUnauthorized();
});

it('driver with incomplete profile cannot get active ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/active');
    $response->assertForbidden();
});

it('rider cannot get active ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/active');
    $response->assertForbidden();
});
