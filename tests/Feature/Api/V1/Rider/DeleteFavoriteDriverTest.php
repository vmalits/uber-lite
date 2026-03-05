<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteDriver;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;

test('can delete favorite driver', function (): void {
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

    $favorite = FavoriteDriver::factory()->create([
        'user_id'   => $user->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($user)
        ->deleteJson(route('api.v1.rider.favorite-drivers.destroy', $favorite))
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
        ]);

    expect(FavoriteDriver::where('id', $favorite->id)->exists())->toBeFalse();
});

test('cannot delete another user favorite driver', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $favorite = FavoriteDriver::factory()->create([
        'user_id'   => $otherUser->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($user)
        ->deleteJson(route('api.v1.rider.favorite-drivers.destroy', $favorite))
        ->assertStatus(403);

    expect(FavoriteDriver::where('id', $favorite->id)->exists())->toBeTrue();
});

test('unauthenticated user cannot delete favorite driver', function (): void {
    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $favorite = FavoriteDriver::factory()->create([
        'user_id'   => $user->id,
        'driver_id' => $driver->id,
    ]);

    deleteJson(route('api.v1.rider.favorite-drivers.destroy', $favorite))
        ->assertStatus(401);
});

test('driver cannot access rider favorite drivers delete endpoint', function (): void {
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

    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $favorite = FavoriteDriver::factory()->create([
        'user_id'   => $user->id,
        'driver_id' => $anotherDriver->id,
    ]);

    actingAs($driver)
        ->deleteJson(route('api.v1.rider.favorite-drivers.destroy', $favorite))
        ->assertStatus(403);
});
