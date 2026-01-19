<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Vehicle;

test('successfully adds a vehicle for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $data = [
        'brand'        => 'Toyota',
        'model'        => 'Camry',
        'year'         => 2022,
        'color'        => 'Black',
        'plate_number' => 'AA1234BB',
        'vehicle_type' => 'sedan',
        'seats'        => 4,
    ];

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/vehicle', $data);

    $response->assertCreated()
        ->assertJsonFragment([
            'message' => 'Vehicle added successfully.',
        ])
        ->assertJsonFragment($data);

    $this->assertDatabaseHas('vehicles', [
        'driver_id'    => $driver->id,
        'brand'        => 'Toyota',
        'model'        => 'Camry',
        'year'         => 2022,
        'color'        => 'Black',
        'plate_number' => 'AA1234BB',
        'vehicle_type' => 'sedan',
        'seats'        => 4,
    ]);
});

test('validates required fields', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/vehicle', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['brand', 'model', 'year', 'color', 'plate_number', 'vehicle_type', 'seats']);
});

test('validates unique plate number', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Vehicle::factory()->create([
        'plate_number' => 'DUP123',
    ]);

    $data = [
        'brand'        => 'Toyota',
        'model'        => 'Camry',
        'year'         => 2022,
        'color'        => 'Black',
        'plate_number' => 'DUP123',
        'vehicle_type' => 'sedan',
        'seats'        => 4,
    ];

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/vehicle', $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['plate_number']);
});

test('returns unauthorized without authentication', function (): void {
    $response = $this->postJson('/api/v1/driver/vehicle', []);

    $response->assertUnauthorized();
});

test('normalizes plate number', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $data = [
        'brand'        => 'Toyota',
        'model'        => 'Camry',
        'year'         => 2022,
        'color'        => 'Black',
        'plate_number' => 'aa-1234 bb',
        'vehicle_type' => 'sedan',
        'seats'        => 4,
    ];

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/vehicle', $data);

    $response->assertCreated();

    $this->assertDatabaseHas('vehicles', [
        'driver_id'    => $driver->id,
        'plate_number' => 'AA1234BB',
    ]);
});

test('returns forbidden for rider role', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($rider)->postJson('/api/v1/driver/vehicle', [
        'brand'        => 'Toyota',
        'model'        => 'Camry',
        'year'         => 2022,
        'color'        => 'Black',
        'plate_number' => 'RIDER123',
        'vehicle_type' => 'sedan',
        'seats'        => 4,
    ]);

    $response->assertForbidden();
});
