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
});

test('admin can get analytics overview', function (): void {
    // Create riders
    $riders = User::factory()->count(5)->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create drivers
    $drivers = User::factory()->count(3)->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create completed rides
    Ride::factory()->count(10)->create([
        'rider_id'     => $riders->first()->id,
        'driver_id'    => $drivers->first()->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 100,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/overview');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'daily_active_users',
                'monthly_active_users',
                'total_revenue',
                'today_revenue',
                'total_rides',
                'today_rides',
                'total_users',
                'total_drivers',
            ],
        ]);
});

test('analytics overview counts daily active users correctly', function (): void {
    $rider1 = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $rider2 = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides for today
    Ride::factory()->create([
        'rider_id'     => $rider1->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);
    Ride::factory()->create([
        'rider_id'     => $rider2->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    // Create ride from yesterday (should not count in DAU)
    Ride::factory()->create([
        'rider_id'     => $rider1->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now()->subDay(),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/overview');

    $response->assertOk()
        ->assertJsonPath('data.daily_active_users', 2);
});

test('analytics overview calculates revenue correctly', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides with specific prices
    Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 500,
        'completed_at' => now(),
    ]);
    Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 300,
        'completed_at' => now()->subDays(2),
    ]);

    Sanctum::actingAs($this->admin);

    $response = $this->getJson('/api/v1/admin/analytics/overview');

    $response->assertOk()
        ->assertJsonPath('data.today_revenue', 500)
        ->assertJsonPath('data.total_revenue', 800);
});

test('non-admin cannot access analytics overview', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/admin/analytics/overview');

    $response->assertForbidden();
});

test('unauthenticated user cannot access analytics overview', function (): void {
    $response = $this->getJson('/api/v1/admin/analytics/overview');

    $response->assertUnauthorized();
});
