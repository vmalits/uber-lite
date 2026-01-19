<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Vehicle;

test('successfully deletes a vehicle for driver', function (): void {
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

    $response = $this->actingAs($driver)->deleteJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertOk()
        ->assertJsonFragment([
            'message' => 'Deleted successfully',
        ]);

    $this->assertDatabaseMissing('vehicles', [
        'id' => $vehicle->id,
    ]);
});

test('returns forbidden when deleting someone else\'s vehicle', function (): void {
    $driver1 = User::factory()->create(['role' => UserRole::DRIVER]);
    $driver2 = User::factory()->create(['role' => UserRole::DRIVER]);

    $vehicle = Vehicle::factory()->create([
        'driver_id' => $driver2->id,
    ]);

    $response = $this->actingAs($driver1)->deleteJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
    ]);
});

test('returns forbidden for rider role on DELETE', function (): void {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $vehicle = Vehicle::factory()->create();

    $response = $this->actingAs($rider)->deleteJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
    ]);
});

test('returns unauthorized for unauthenticated user on DELETE', function (): void {
    $vehicle = Vehicle::factory()->create();

    $response = $this->deleteJson("/api/v1/driver/vehicle/{$vehicle->id}");

    $response->assertUnauthorized();

    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
    ]);
});
