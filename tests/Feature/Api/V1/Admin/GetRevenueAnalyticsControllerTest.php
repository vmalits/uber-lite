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

test('admin can get revenue analytics', function (): void {
    Ride::factory()->count(10)->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/revenue');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_revenue',
                'today_revenue',
                'week_revenue',
                'month_revenue',
                'average_daily_revenue',
                'average_ride_price',
                'total_discounts',
                'growth_rate',
                'daily_revenue',
                'monthly_revenue',
            ],
        ]);
});

test('revenue analytics calculates today revenue correctly', function (): void {
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 500,
        'completed_at' => now(),
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 300,
        'completed_at' => now()->subDay(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/revenue?days=7');

    $response->assertOk()
        ->assertJsonPath('data.today_revenue', 500);
});

test('revenue analytics returns daily stats with correct count', function (): void {
    Ride::factory()->count(5)->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/revenue?days=7');

    $response->assertOk();

    $dailyRevenue = $response->json('data.daily_revenue');
    expect(count($dailyRevenue))->toBe(7);
});

test('revenue analytics calculates average ride price correctly', function (): void {
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

    $response = $this->getJson('/api/v1/admin/analytics/revenue?days=1');

    $response->assertOk()
        ->assertJsonPath('data.average_ride_price', 150);
});

test('revenue analytics calculates total revenue correctly', function (): void {
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 1000,
        'completed_at' => now()->subDays(5),
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 500,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/revenue');

    $response->assertOk()
        ->assertJsonPath('data.total_revenue', 1500);
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

    $response = $this->getJson('/api/v1/admin/analytics/revenue?days=14');

    $response->assertOk();

    $dailyRevenue = $response->json('data.daily_revenue');
    expect(count($dailyRevenue))->toBe(14);
});

test('non-admin cannot access revenue analytics', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/admin/analytics/revenue');

    $response->assertForbidden();
});

test('unauthenticated user cannot access revenue analytics', function (): void {
    $response = $this->getJson('/api/v1/admin/analytics/revenue');

    $response->assertUnauthorized();
});

test('validation fails for invalid days parameter', function (): void {
    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/revenue?days=0');

    $response->assertUnprocessable();
});

test('validation fails for days parameter exceeding max', function (): void {
    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/revenue?days=400');

    $response->assertUnprocessable();
});
