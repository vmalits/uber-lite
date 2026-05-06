<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('admin can assign driver to pending ride', function (): void {
    $admin = createAdmin();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING, 'driver_id' => null]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
            'driver_id' => $driver->id,
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.driver_id', $driver->id)
        ->assertJsonPath('data.status', 'accepted');

    $this->assertDatabaseHas('rides', [
        'id'        => $ride->id,
        'driver_id' => $driver->id,
        'status'    => RideStatus::ACCEPTED->value,
    ]);

    expect($ride->refresh()->ride_pin)->not->toBeNull();
});

test('admin cannot assign driver to non-pending ride', function (): void {
    $admin = createAdmin();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $ride = Ride::factory()->create(['status' => RideStatus::ACCEPTED, 'driver_id' => null]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
            'driver_id' => $driver->id,
        ])
        ->assertForbidden();
});

test('admin cannot assign driver to ride with existing driver', function (): void {
    $admin = createAdmin();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $existingDriver = User::factory()->create(['role' => UserRole::DRIVER]);
    $ride = Ride::factory()->create([
        'status'    => RideStatus::PENDING,
        'driver_id' => $existingDriver->id,
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
            'driver_id' => $driver->id,
        ])
        ->assertForbidden();
});

test('driver_id must be a valid driver', function (): void {
    $admin = createAdmin();
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING, 'driver_id' => null]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
            'driver_id' => $rider->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['driver_id']);
});

test('driver_id must exist', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING, 'driver_id' => null]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
            'driver_id' => '01JKFAKE000000000000000000',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['driver_id']);
});

test('driver_id is required', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING, 'driver_id' => null]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['driver_id']);
});

test('non-admin cannot assign driver', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING, 'driver_id' => null]);

    actingAs($rider)
        ->postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
            'driver_id' => $driver->id,
        ])
        ->assertForbidden();
});

test('unauthenticated user cannot assign driver', function (): void {
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING, 'driver_id' => null]);

    postJson("/api/v1/admin/rides/{$ride->id}/assign-driver", [
        'driver_id' => '01JKFAKE000000000000000000',
    ])
        ->assertUnauthorized();
});
