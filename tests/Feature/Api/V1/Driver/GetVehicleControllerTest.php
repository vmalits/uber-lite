<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Str;

test('successfully returns vehicle details for driver', function (): void {
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

    $response = $this->actingAs($driver)->getJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
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
        ])
        ->assertJsonPath('data.id', $vehicle->id);
});

test('returns forbidden when trying to view another drivers vehicle', function (): void {
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

    $vehicle = Vehicle::factory()->create(['driver_id' => $driver2->id]);

    $response = $this->actingAs($driver1)->getJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertForbidden();
});

test('returns 404 if vehicle does not exist', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $uuid = (string) Str::ulid();

    $response = $this->actingAs($driver)->getJson("/api/v1/driver/vehicle/{$uuid}");

    $response->assertNotFound();
});

test('returns unauthorized without authentication', function (): void {
    $vehicle = Vehicle::factory()->create();
    $response = $this->getJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertUnauthorized();
});
