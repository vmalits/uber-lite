<?php

declare(strict_types=1);

use App\Enums\DriverAvailabilityStatus;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverLocation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can go online with valid coordinates', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ];

    $response = $this->postJson('/api/v1/driver/online', $payload);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'You are now online and available for rides.',
        ])
        ->assertJsonPath('data.driver_id', $user->id)
        ->assertJsonPath('data.status', 'online')
        ->assertJsonPath('data.lat', 47.0105)
        ->assertJsonPath('data.lng', 28.8638);

    $location = DriverLocation::where('driver_id', $user->id)->first();

    expect($location)->not->toBeNull()
        ->and($location->status)->toBe(DriverAvailabilityStatus::ONLINE)
        ->and($location->lat)->toBe(47.0105)
        ->and($location->lng)->toBe(28.8638);
});

it('creates location record if driver has none', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    expect(DriverLocation::where('driver_id', $user->id)->count())->toBe(0);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    expect(DriverLocation::where('driver_id', $user->id)->count())->toBe(1);
});

it('updates existing location when going online', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    DriverLocation::factory()->create([
        'driver_id' => $user->id,
        'lat'       => 10.0,
        'lng'       => 20.0,
        'status'    => DriverAvailabilityStatus::OFFLINE,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertOk();

    $location = DriverLocation::where('driver_id', $user->id)->first();

    expect($location->lat)->toBe(47.0105)
        ->and($location->lng)->toBe(28.8638)
        ->and($location->status)->toBe(DriverAvailabilityStatus::ONLINE);
});

it('denies online for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertForbidden();
});

it('denies online for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertForbidden();
});

it('denies online for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertUnauthorized();
});

it('validates required fields', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['latitude', 'longitude']);
});

it('validates latitude range', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 100,
        'longitude' => 28.8638,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['latitude']);
});

it('validates longitude range', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 200,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['longitude']);
});
