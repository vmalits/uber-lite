<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverLocation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver with completed profile can update location', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $location = DriverLocation::factory()->create([
        'driver_id' => $user->id,
        'lat'       => 10.0,
        'lng'       => 20.0,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'lat' => 55.7558,
        'lng' => 37.6173,
    ];

    $response = $this->postJson('/api/v1/driver/location', $payload);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Driver location updated successfully.',
        ])
        ->assertJsonPath('data.driver_id', $user->id)
        ->assertJsonPath('data.lat', 55.7558)
        ->assertJsonPath('data.lng', 37.6173);

    $location->refresh();

    expect($location->lat)->toBe(55.7558)
        ->and($location->lng)->toBe(37.6173);
});

it('denies update for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 10.0,
        'lng' => 20.0,
    ]);

    $response->assertForbidden();
});

it('denies update for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 10.0,
        'lng' => 20.0,
    ]);

    $response->assertForbidden();
});

it('denies update for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 10.0,
        'lng' => 20.0,
    ]);

    $response->assertUnauthorized();
});

it('validates required fields', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/location', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['lat', 'lng']);
});
