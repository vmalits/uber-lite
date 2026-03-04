<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('rider can get receipts', function (): void {
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

    Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/receipts')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('receipts only shows completed rides', function (): void {
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

    Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::CANCELLED,
    ]);

    Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::STARTED,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/receipts')
        ->assertStatus(200)
        ->assertJsonPath('data.pagination.total', 1);
});

test('rider can filter receipts by date', function (): void {
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

    Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now()->subDays(10),
    ]);

    Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 20000,
        'completed_at' => now()->subDays(5),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/receipts?from='.now()->subDays(7)->format('Y-m-d').'&to='.now()->format('Y-m-d'))
        ->assertStatus(200)
        ->assertJsonPath('data.pagination.total', 1);
});

test('unauthenticated user cannot access receipts', function (): void {
    getJson('/api/v1/rider/receipts')
        ->assertStatus(401);
});

test('driver cannot access rider receipts', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/rider/receipts')
        ->assertStatus(403);
});

test('rider can get single receipt', function (): void {
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

    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/receipts/'.$ride->id)
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $ride->id);
});

test('rider cannot get receipt for incomplete ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::STARTED,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/receipts/'.$ride->id)
        ->assertStatus(403);
});

test('rider cannot get receipt for another riders ride', function (): void {
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

    $ride = Ride::factory()->create([
        'rider_id'     => $otherRider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/receipts/'.$ride->id)
        ->assertStatus(403);
});
