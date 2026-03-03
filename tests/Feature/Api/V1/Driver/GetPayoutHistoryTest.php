<?php

declare(strict_types=1);

use App\Enums\PayoutStatus;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverPayout;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('driver can get payout history', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    DriverPayout::factory()->create(['driver_id' => $driver->id]);

    actingAs($driver)
        ->getJson('/api/v1/driver/payouts')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('driver can filter payouts by status', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    DriverPayout::factory()->create([
        'driver_id' => $driver->id,
        'status'    => PayoutStatus::COMPLETED,
    ]);

    DriverPayout::factory()->create([
        'driver_id' => $driver->id,
        'status'    => PayoutStatus::PENDING,
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/payouts?status=completed')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('driver can request payout', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
        'driver_balance'    => 10000,
    ]);

    actingAs($driver)
        ->postJson('/api/v1/driver/payouts', [
            'amount'              => 5000,
            'method'              => 'bank_transfer',
            'bank_name'           => 'Test Bank',
            'bank_account_number' => '1234567890',
            'bank_routing_number' => '021000021',
        ])
        ->assertStatus(200)
        ->assertJsonPath('success', true);

    $driver->refresh();
    expect($driver->driver_balance)->toBe(5000);
});

test('driver cannot request payout with insufficient balance', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
        'driver_balance'    => 100,
    ]);

    actingAs($driver)
        ->postJson('/api/v1/driver/payouts', [
            'amount'              => 5000,
            'method'              => 'bank_transfer',
            'bank_name'           => 'Test Bank',
            'bank_account_number' => '1234567890',
            'bank_routing_number' => '021000021',
        ])
        ->assertStatus(422);
});

test('driver can get balance', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
        'driver_balance'    => 10000,
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/balance')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.balance', 10000);
});

test('unauthenticated user cannot access payouts', function (): void {
    getJson('/api/v1/driver/payouts')
        ->assertStatus(401);
});

test('rider cannot access driver payouts', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/driver/payouts')
        ->assertStatus(403);
});
