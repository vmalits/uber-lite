<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\RideTip;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('driver can get tip history', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->create([
        'driver_id' => $driver->id,
        'amount'    => 500,
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/tips')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('driver can filter tips by date range', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->create([
        'driver_id'  => $driver->id,
        'created_at' => now()->subDays(5),
    ]);

    RideTip::factory()->create([
        'driver_id'  => $driver->id,
        'created_at' => now()->subDays(10),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/tips?from='.now()->subDays(7)->format('Y-m-d').'&to='.now()->format('Y-m-d'))
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('driver sees only own tips', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $otherDriver */
    $otherDriver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->create([
        'driver_id' => $driver->id,
        'amount'    => 1000,
    ]);

    RideTip::factory()->create([
        'driver_id' => $otherDriver->id,
        'amount'    => 2000,
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/tips')
        ->assertStatus(200);
});

test('unauthenticated user cannot access tips', function (): void {
    getJson('/api/v1/driver/tips')
        ->assertStatus(401);
});

test('rider cannot access driver tips', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/driver/tips')
        ->assertStatus(403);
});

test('empty tips returns empty array', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/tips')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});
