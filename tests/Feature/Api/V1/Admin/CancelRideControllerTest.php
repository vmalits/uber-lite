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

test('admin can cancel pending ride', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'No available drivers',
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'cancelled');

    $this->assertDatabaseHas('rides', [
        'id'     => $ride->id,
        'status' => RideStatus::CANCELLED->value,
    ]);

    $ride->refresh();
    expect($ride->cancelled_at)->not->toBeNull()
        ->and($ride->cancelled_by_type->value)->toBe('admin')
        ->and($ride->cancelled_reason)->toBe('No available drivers');
});

test('admin can cancel accepted ride', function (): void {
    $admin = createAdmin();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $ride = Ride::factory()->create(['status' => RideStatus::ACCEPTED, 'driver_id' => $driver->id]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'Driver unresponsive',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'cancelled');
});

test('admin can cancel started ride', function (): void {
    $admin = createAdmin();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $ride = Ride::factory()->create([
        'status'     => RideStatus::STARTED,
        'driver_id'  => $driver->id,
        'started_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'Safety concern reported',
        ])
        ->assertOk();
});

test('admin cannot cancel completed ride', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create([
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'Some reason',
        ])
        ->assertForbidden();
});

test('admin cannot cancel already cancelled ride', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create([
        'status'       => RideStatus::CANCELLED,
        'cancelled_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'Double cancel',
        ])
        ->assertForbidden();
});

test('reason is required', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['reason']);
});

test('reason must not exceed 500 chars', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => str_repeat('a', 501),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['reason']);
});

test('non-admin cannot cancel ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    actingAs($rider)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'Some reason',
        ])
        ->assertForbidden();
});

test('unauthenticated user cannot cancel ride', function (): void {
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
        'reason' => 'Some reason',
    ])
        ->assertUnauthorized();
});

test('cancelled_by_id is set to admin user id', function (): void {
    $admin = createAdmin();
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    actingAs($admin)
        ->postJson("/api/v1/admin/rides/{$ride->id}/cancel", [
            'reason' => 'Admin override',
        ])
        ->assertOk();

    expect($ride->refresh()->cancelled_by_id)->toBe($admin->id);
});
