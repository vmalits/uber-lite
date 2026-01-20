<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Vehicle;

test('successfully returns list of vehicles for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $vehicles = Vehicle::factory()->count(2)->create([
        'driver_id' => $driver->id,
    ]);

    $response = $this->actingAs($driver)->getJson('/api/v1/driver/vehicles');

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'brand',
                    'model',
                    'year',
                    'color',
                    'plate_number',
                    'vehicle_type',
                    'seats',
                    'created_at' => ['human', 'string'],
                    'updated_at' => ['human', 'string'],
                ],
            ],
        ]);

    $response->assertJsonFragment(['id' => $vehicles[0]->id]);
    $response->assertJsonFragment(['id' => $vehicles[1]->id]);
});

test('returns empty list if driver has no vehicles', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($driver)->getJson('/api/v1/driver/vehicles');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

test('does not return other drivers vehicles', function (): void {
    $driver1 = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $driver2 = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Vehicle::factory()->create(['driver_id' => $driver2->id]);

    $response = $this->actingAs($driver1)->getJson('/api/v1/driver/vehicles');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

test('returns forbidden for rider role', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($rider)->getJson('/api/v1/driver/vehicles');

    $response->assertForbidden();
});

test('returns unauthorized without authentication', function (): void {
    $response = $this->getJson('/api/v1/driver/vehicles');

    $response->assertUnauthorized();
});
