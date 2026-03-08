<?php

declare(strict_types=1);

use App\Enums\DriverAvailabilityStatus;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\DriverLocation;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

it('returns real-time analytics for admin', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    Cache::flush();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/analytics/real-time');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'active_rides',
                'pending_rides',
                'online_drivers',
                'busy_drivers',
                'total_riders_online',
                'current_surge_multiplier',
                'rides_last_hour',
                'completed_rides_today',
                'cancelled_rides_today',
                'rides_by_status',
                'drivers_by_area',
            ],
        ]);
});

it('counts active rides correctly', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    Ride::factory()->count(3)->create(['status' => RideStatus::STARTED]);
    Ride::factory()->count(2)->create(['status' => RideStatus::ACCEPTED]);

    Cache::flush();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/analytics/real-time');

    $response->assertOk();
    expect($response['data']['active_rides'])->toBe(5);
});

it('counts online drivers correctly', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    DriverLocation::factory()->count(4)->create(['status' => DriverAvailabilityStatus::ONLINE]);
    DriverLocation::factory()->count(2)->create(['status' => DriverAvailabilityStatus::BUSY]);

    Cache::flush();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/analytics/real-time');

    $response->assertOk();
    expect($response['data']['online_drivers'])->toBe(4)
        ->and($response['data']['busy_drivers'])->toBe(2);
});

it('rejects unauthorized request without token', function () {
    $response = $this->getJson('/api/v1/admin/analytics/real-time');
    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/admin/analytics/real-time');
    $response->assertForbidden();
});
