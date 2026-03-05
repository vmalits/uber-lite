<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteDriver;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can add favorite driver', function (): void {
    $user = User::factory()->create([
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

    actingAs($user)
        ->postJson(route('api.v1.rider.favorite-drivers.store'), [
            'driver_id' => $driver->id,
        ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'favorite' => [
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

    expect(FavoriteDriver::where('user_id', $user->id)->where('driver_id', $driver->id)->exists())->toBeTrue();
});

test('validation fails for missing driver_id', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($user)
        ->postJson(route('api.v1.rider.favorite-drivers.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['driver_id']);
});

test('validation fails for invalid driver_id', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($user)
        ->postJson(route('api.v1.rider.favorite-drivers.store'), [
            'driver_id' => 'invalid-id',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['driver_id']);
});

test('validation fails when adding non-driver user', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherRider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    actingAs($user)
        ->postJson(route('api.v1.rider.favorite-drivers.store'), [
            'driver_id' => $otherRider->id,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['driver_id']);
});

test('cannot add same driver twice', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    FavoriteDriver::factory()->create([
        'user_id'   => $user->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($user)
        ->postJson(route('api.v1.rider.favorite-drivers.store'), [
            'driver_id' => $driver->id,
        ])
        ->assertStatus(422);
});

test('unauthenticated user cannot add favorite driver', function (): void {
    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->postJson(route('api.v1.rider.favorite-drivers.store'), [
        'driver_id' => $driver->id,
    ])
        ->assertStatus(401);
});

test('driver cannot access rider favorite drivers endpoint', function (): void {
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
        ->postJson(route('api.v1.rider.favorite-drivers.store'), [
            'driver_id' => $anotherDriver->id,
        ])
        ->assertStatus(403);
});
