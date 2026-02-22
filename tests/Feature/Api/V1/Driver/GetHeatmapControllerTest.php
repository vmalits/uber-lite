<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns heatmap data for driver', function () {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    // Create some rides
    Ride::factory()->create([
        'status'     => RideStatus::PENDING,
        'origin_lat' => 47.0105,
        'origin_lng' => 28.8638,
    ]);

    Ride::factory()->create([
        'status'     => RideStatus::PENDING,
        'origin_lat' => 47.0106, // Close enough to round to 47.011
        'origin_lng' => 28.8639,
    ]);

    Ride::factory()->create([
        'status'       => RideStatus::SCHEDULED,
        'origin_lat'   => 47.100,
        'origin_lng'   => 28.100,
        'scheduled_at' => now()->addMinutes(10),
    ]);

    // This one should be excluded (scheduled too far in future)
    Ride::factory()->create([
        'status'       => RideStatus::SCHEDULED,
        'origin_lat'   => 48.000,
        'origin_lng'   => 29.000,
        'scheduled_at' => now()->addHours(2),
    ]);

    // This one should be excluded (not PENDING/SCHEDULED)
    Ride::factory()->create([
        'status'     => RideStatus::ACCEPTED,
        'origin_lat' => 47.010,
        'origin_lng' => 28.863,
    ]);

    $response = $this->getJson('/api/v1/driver/heatmap');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['lat', 'lng', 'intensity', 'weight'],
            ],
        ]);

    $data = $response->json('data');
    expect(\count($data))->toBe(2); // 47.011/28.864 (intensity 2) and 47.100/28.100 (intensity 1)
});

it('denies heatmap access to rider', function () {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/driver/heatmap');

    $response->assertForbidden();
});
