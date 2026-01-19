<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Vehicle;

test('successfully updates a vehicle for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $vehicle = Vehicle::factory()->create([
        'driver_id' => $driver->id,
    ]);

    $data = [
        'brand'        => 'BMW',
        'model'        => 'X5',
        'year'         => 2021,
        'color'        => 'White',
        'plate_number' => 'UPDATED123',
        'vehicle_type' => 'sedan',
        'seats'        => 5,
    ];

    $response = $this->actingAs($driver)->putJson("/api/v1/driver/vehicle/{$vehicle->id}", $data);

    $response->assertOk()
        ->assertJsonFragment([
            'message' => 'Updated successfully',
        ])
        ->assertJsonFragment($data);

    $this->assertDatabaseHas('vehicles', [
        'id'           => $vehicle->id,
        'brand'        => 'BMW',
        'plate_number' => 'UPDATED123',
    ]);
});

test('returns forbidden when updating someone else\'s vehicle', function (): void {
    $driver1 = User::factory()->create(['role' => UserRole::DRIVER]);
    $driver2 = User::factory()->create(['role' => UserRole::DRIVER]);

    $vehicle = Vehicle::factory()->create([
        'driver_id' => $driver2->id,
    ]);

    $response = $this->actingAs($driver1)->putJson("/api/v1/driver/vehicle/{$vehicle->id}", [
        'brand'        => 'BMW',
        'model'        => 'X5',
        'year'         => 2021,
        'color'        => 'White',
        'plate_number' => 'OTHER123',
        'vehicle_type' => 'sedan',
        'seats'        => 5,
    ]);

    $response->assertForbidden();
});

test('returns forbidden for rider role on PUT', function (): void {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($rider)->putJson("/api/v1/driver/vehicle/{$vehicle->id}", [
        'brand'        => 'BMW',
        'model'        => 'X5',
        'year'         => 2021,
        'color'        => 'White',
        'plate_number' => 'RIDER_UP',
        'vehicle_type' => 'sedan',
        'seats'        => 5,
    ]);

    $response->assertForbidden();
});

test('allows updating with the same plate number', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $vehicle = Vehicle::factory()->create([
        'driver_id'    => $driver->id,
        'plate_number' => 'SAME123',
    ]);

    $data = [
        'brand'        => 'BMW',
        'model'        => 'X5',
        'year'         => 2021,
        'color'        => 'White',
        'plate_number' => 'SAME123',
        'vehicle_type' => 'sedan',
        'seats'        => 5,
    ];

    $response = $this->actingAs($driver)->putJson("/api/v1/driver/vehicle/{$vehicle->id}", $data);

    $response->assertOk();
});
