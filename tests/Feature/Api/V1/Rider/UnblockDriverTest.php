<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\BlockedDriver;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can unblock a driver', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $blocked = BlockedDriver::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($rider)
        ->deleteJson(route('api.v1.rider.blocked-drivers.destroy', $blocked))
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
        ]);

    expect(BlockedDriver::where('id', $blocked->id)->exists())->toBeFalse();
});

test('cannot unblock another rider blocked driver', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherRider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $blocked = BlockedDriver::factory()->create([
        'rider_id'  => $otherRider->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($rider)
        ->deleteJson(route('api.v1.rider.blocked-drivers.destroy', $blocked))
        ->assertStatus(403);

    expect(BlockedDriver::where('id', $blocked->id)->exists())->toBeTrue();
});

test('unauthenticated user cannot unblock driver', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $blocked = BlockedDriver::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    $this->deleteJson(route('api.v1.rider.blocked-drivers.destroy', $blocked))
        ->assertStatus(401);
});

test('driver cannot access rider unblock endpoint', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $rider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $anotherDriver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $blocked = BlockedDriver::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $anotherDriver->id,
    ]);

    actingAs($driver)
        ->deleteJson(route('api.v1.rider.blocked-drivers.destroy', $blocked))
        ->assertStatus(403);
});
