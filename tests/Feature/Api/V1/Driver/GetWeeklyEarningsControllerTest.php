<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('driver can get weekly earnings', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 500,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/earnings/weekly');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_earnings',
                'total_rides',
                'average_per_hour',
                'best_day',
                'best_day_earnings',
                'comparison_to_previous',
            ],
        ]);
});

test('driver can specify number of weeks', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 200,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/earnings/weekly?weeks=2');

    $response->assertOk();
});

test('unauthorized user cannot access weekly earnings', function (): void {
    $response = $this->getJson('/api/v1/driver/earnings/weekly');

    $response->assertUnauthorized();
});

test('rider cannot access driver weekly earnings', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/driver/earnings/weekly');

    $response->assertForbidden();
});

test('weeks parameter must be between 1 and 12', function (): void {
    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/earnings/weekly?weeks=20');

    $response->assertUnprocessable();
});
