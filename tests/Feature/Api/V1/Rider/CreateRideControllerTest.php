<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('allows access for rider with completed profile and creates ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $rideData = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/rides', $rideData);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Ride created successfully.',
            'data'    => [
                'rider_id'            => $user->id,
                'origin_address'      => $rideData['origin_address'],
                'origin_lat'          => $rideData['origin_lat'],
                'origin_lng'          => $rideData['origin_lng'],
                'destination_address' => $rideData['destination_address'],
                'destination_lat'     => $rideData['destination_lat'],
                'destination_lng'     => $rideData['destination_lng'],
                'status'              => RideStatus::PENDING->value,
            ],
        ]);

    $this->assertDatabaseHas('rides', [
        'rider_id'       => $user->id,
        'origin_address' => $rideData['origin_address'],
        'status'         => RideStatus::PENDING->value,
    ]);
});

test('validates request data', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/rides', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'origin_address',
            'origin_lat',
            'origin_lng',
            'destination_address',
            'destination_lat',
            'destination_lng',
        ]);
});

test('denies access for non-rider', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/rides');

    $response->assertForbidden();
});

test('denies access if profile not completed', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/rides');

    $response->assertForbidden()
        ->assertJsonPath('message', 'Forbidden. Profile step not completed.');
});

test('denies access if unauthenticated', function (): void {
    $response = $this->postJson('/api/v1/rider/rides');

    $response->assertUnauthorized();
});
