<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteDriver;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can get favorite drivers', function (): void {
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

    FavoriteDriver::factory()->create([
        'user_id'   => $user->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorite-drivers.index'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'driver_id',
                        'first_name',
                        'last_name',
                        'avatar_paths',
                        'created_at' => ['human', 'string'],
                        'updated_at' => ['human', 'string'],
                    ],
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ]);
});

test('favorite drivers are paginated', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    FavoriteDriver::factory()->count(15)->create(['user_id' => $user->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorite-drivers.index').'?per_page=10')
        ->assertStatus(200)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.total', 15);
});

test('only own favorite drivers are returned', function (): void {
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

    FavoriteDriver::factory()->count(3)->create(['user_id' => $user->id]);
    FavoriteDriver::factory()->count(5)->create(['user_id' => $otherUser->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorite-drivers.index'))
        ->assertStatus(200)
        ->assertJsonCount(3, 'data.items');
});

test('favorite drivers are ordered by created_at desc', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $first = FavoriteDriver::factory()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subHours(3),
    ]);

    $second = FavoriteDriver::factory()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subHours(2),
    ]);

    $third = FavoriteDriver::factory()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subHours(1),
    ]);

    $response = actingAs($user)
        ->getJson(route('api.v1.rider.favorite-drivers.index'))
        ->assertStatus(200);

    $ids = collect($response->json('data.items'))->pluck('id')->toArray();

    expect($ids)->toBe([$third->id, $second->id, $first->id]);
});

test('unauthenticated user cannot access favorite drivers', function (): void {
    getJson(route('api.v1.rider.favorite-drivers.index'))
        ->assertStatus(401);
});

test('driver cannot access rider favorite drivers', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson(route('api.v1.rider.favorite-drivers.index'))
        ->assertStatus(403);
});
