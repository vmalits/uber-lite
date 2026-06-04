<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\BlockedDriver;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can block a driver', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'first_name'   => 'John',
        'last_name'    => 'Doe',
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.blocked-drivers.store'), [
            'driver_id' => $driver->id,
        ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'blocked' => [
                    'id',
                    'driver_id',
                    'first_name',
                    'last_name',
                    'avatar_paths',
                    'created_at' => ['human', 'string'],
                    'updated_at' => ['human', 'string'],
                ],
            ],
            'message',
        ]);

    expect(BlockedDriver::where('rider_id', $rider->id)->where('driver_id', $driver->id)->exists())->toBeTrue();
});

test('validation fails for missing driver_id', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.blocked-drivers.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['driver_id']);
});

test('validation fails for invalid driver_id', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.blocked-drivers.store'), [
            'driver_id' => 'invalid-id',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['driver_id']);
});

test('validation fails when blocking non-driver user', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherRider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.blocked-drivers.store'), [
            'driver_id' => $otherRider->id,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['driver_id']);
});

test('cannot block same driver twice', function (): void {
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

    BlockedDriver::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.blocked-drivers.store'), [
            'driver_id' => $driver->id,
        ])
        ->assertStatus(422);
});

test('unauthenticated user cannot block driver', function (): void {
    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->postJson(route('api.v1.rider.blocked-drivers.store'), [
        'driver_id' => $driver->id,
    ])->assertStatus(401);
});

test('driver cannot access rider block endpoint', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $anotherDriver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    actingAs($driver)
        ->postJson(route('api.v1.rider.blocked-drivers.store'), [
            'driver_id' => $anotherDriver->id,
        ])
        ->assertStatus(403);
});
