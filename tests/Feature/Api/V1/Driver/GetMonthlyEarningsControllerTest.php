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

test('driver can get monthly earnings', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 5000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/earnings/monthly');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_earnings',
                'total_rides',
                'average_per_hour',
                'best_month',
                'best_month_earnings',
                'comparison_to_previous',
            ],
        ]);
});

test('driver can specify number of months', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 2000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/earnings/monthly?months=6');

    $response->assertOk();
});

test('unauthorized user cannot access monthly earnings', function (): void {
    $response = $this->getJson('/api/v1/driver/earnings/monthly');

    $response->assertUnauthorized();
});

test('rider cannot access driver monthly earnings', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/driver/earnings/monthly');

    $response->assertForbidden();
});

test('months parameter must be between 1 and 12', function (): void {
    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/earnings/monthly?months=20');

    $response->assertUnprocessable();
});
