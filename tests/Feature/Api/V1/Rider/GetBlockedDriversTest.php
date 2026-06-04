<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\BlockedDriver;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can get blocked drivers', function (): void {
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

    BlockedDriver::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($rider)
        ->getJson(route('api.v1.rider.blocked-drivers.index'))
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

test('blocked drivers are paginated', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    BlockedDriver::factory()->count(15)->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->getJson(route('api.v1.rider.blocked-drivers.index').'?per_page=10')
        ->assertStatus(200)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.total', 15);
});

test('only own blocked drivers are returned', function (): void {
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

    BlockedDriver::factory()->count(3)->create(['rider_id' => $rider->id]);
    BlockedDriver::factory()->count(5)->create(['rider_id' => $otherRider->id]);

    actingAs($rider)
        ->getJson(route('api.v1.rider.blocked-drivers.index'))
        ->assertStatus(200)
        ->assertJsonCount(3, 'data.items');
});

test('unauthenticated user cannot access blocked drivers', function (): void {
    getJson(route('api.v1.rider.blocked-drivers.index'))
        ->assertStatus(401);
});

test('driver cannot access rider blocked drivers', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson(route('api.v1.rider.blocked-drivers.index'))
        ->assertStatus(403);
});
