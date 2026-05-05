<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can get timeline for completed ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'arrived_at'   => now()->subHours(2),
        'started_at'   => now()->subHours(2)->addMinutes(5),
        'completed_at' => now()->subHours(2)->addMinutes(30),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson("/api/v1/driver/rides/{$ride->id}/timeline");
    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ride_id', $ride->id)
        ->assertJsonPath('data.current_status', 'completed');

    $events = $response->json('data.events');
    $statuses = array_column($events, 'status');

    expect($statuses)->toContain('pending', 'accepted', 'on_the_way', 'arrived', 'started', 'completed');
});

test('driver can get timeline for cancelled ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::CANCELLED,
        'cancelled_at' => now()->subMinutes(10),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson("/api/v1/driver/rides/{$ride->id}/timeline");
    $response->assertOk()
        ->assertJsonPath('data.current_status', 'cancelled');

    $events = $response->json('data.events');
    $statuses = array_column($events, 'status');

    expect($statuses)->toContain('pending', 'cancelled');
});

test('driver cannot get timeline for another drivers ride', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherDriver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'driver_id' => $otherDriver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $this->getJson("/api/v1/driver/rides/{$ride->id}/timeline")
        ->assertForbidden();
});

test('unauthenticated user cannot get driver ride timeline', function (): void {
    $ride = Ride::factory()->create([
        'status' => RideStatus::COMPLETED,
    ]);

    $this->getJson("/api/v1/driver/rides/{$ride->id}/timeline")
        ->assertUnauthorized();
});
