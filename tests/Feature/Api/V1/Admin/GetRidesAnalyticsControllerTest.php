<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->admin = User::factory()->verified()->create([
        'role'         => UserRole::ADMIN,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('admin can get rides analytics', function (): void {
    // Create some completed rides
    Ride::factory()->count(10)->create([
        'rider_id'              => $this->rider->id,
        'driver_id'             => $this->driver->id,
        'status'                => RideStatus::COMPLETED,
        'price'                 => 100,
        'estimated_distance_km' => 10,
        'completed_at'          => now(),
    ]);

    // Create some cancelled rides
    Ride::factory()->count(2)->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => null,
        'status'       => RideStatus::CANCELLED,
        'price'        => null,
        'cancelled_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_rides',
                'completed_rides',
                'cancelled_rides',
                'average_ride_price',
                'average_ride_distance',
                'average_ride_duration',
                'cancellation_rate',
                'daily_stats',
            ],
        ]);
});

test('rides analytics calculates correct totals', function (): void {
    Ride::factory()->count(8)->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Ride::factory()->count(2)->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::CANCELLED,
        'price'        => null,
        'cancelled_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides?days=1');

    $response->assertOk()
        ->assertJsonPath('data.total_rides', 10)
        ->assertJsonPath('data.completed_rides', 8)
        ->assertJsonPath('data.cancelled_rides', 2)
        ->assertJsonPath('data.cancellation_rate', 20);
});

test('rides analytics calculates average price correctly', function (): void {
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 200,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides?days=1');

    $response->assertOk()
        ->assertJsonPath('data.average_ride_price', 150);
});

test('rides analytics returns daily stats with correct count', function (): void {
    Ride::factory()->count(5)->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides?days=7');

    $response->assertOk();

    $dailyStats = $response->json('data.daily_stats');
    expect(count($dailyStats))->toBe(7);
});

test('admin can specify days parameter', function (): void {
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides?days=14');

    $response->assertOk();

    $dailyStats = $response->json('data.daily_stats');
    expect(count($dailyStats))->toBe(14);
});

test('non-admin cannot access rides analytics', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/admin/analytics/rides');

    $response->assertForbidden();
});

test('unauthenticated user cannot access rides analytics', function (): void {
    $response = $this->getJson('/api/v1/admin/analytics/rides');

    $response->assertUnauthorized();
});

test('validation fails for invalid days parameter', function (): void {
    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides?days=0');

    $response->assertUnprocessable();
});

test('validation fails for days parameter exceeding max', function (): void {
    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/rides?days=400');

    $response->assertUnprocessable();
});
