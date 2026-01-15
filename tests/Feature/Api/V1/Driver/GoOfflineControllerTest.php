<?php

declare(strict_types=1);

use App\Enums\DriverAvailabilityStatus;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverLocation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can go offline', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    DriverLocation::factory()->create([
        'driver_id' => $user->id,
        'status'    => DriverAvailabilityStatus::ONLINE,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'You are now offline and will not receive ride requests.',
        ])
        ->assertJsonPath('data.driver_id', $user->id)
        ->assertJsonPath('data.status', 'offline');

    $location = DriverLocation::where('driver_id', $user->id)->first();

    expect($location)->not->toBeNull()
        ->and($location->status)->toBe(DriverAvailabilityStatus::OFFLINE);
});

it('updates existing location when going offline', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    DriverLocation::factory()->create([
        'driver_id' => $user->id,
        'status'    => DriverAvailabilityStatus::ONLINE,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertOk();

    $location = DriverLocation::where('driver_id', $user->id)->first();

    expect($location->status)->toBe(DriverAvailabilityStatus::OFFLINE);
});

it('keeps last known location when going offline', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    DriverLocation::factory()->create([
        'driver_id' => $user->id,
        'lat'       => 47.0105,
        'lng'       => 28.8638,
        'status'    => DriverAvailabilityStatus::ONLINE,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertOk();

    $location = DriverLocation::where('driver_id', $user->id)->first();

    expect($location->lat)->toBe(47.0105)
        ->and($location->lng)->toBe(28.8638)
        ->and($location->status)->toBe(DriverAvailabilityStatus::OFFLINE);
});

it('denies offline for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertForbidden();
});

it('denies offline for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertForbidden();
});

it('denies offline for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertUnauthorized();
});

it('updates last_active_at when going offline', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $oldTime = now()->subMinutes(5);

    DriverLocation::factory()->create([
        'driver_id'      => $user->id,
        'status'         => DriverAvailabilityStatus::ONLINE,
        'last_active_at' => $oldTime,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/driver/offline');

    $location = DriverLocation::where('driver_id', $user->id)->first();

    expect($location->last_active_at->gt($oldTime))->toBeTrue();
});
