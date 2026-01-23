<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverBan;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns active bans for driver', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    DriverBan::factory()->create([
        'driver_id'   => $driver->id,
        'unbanned_at' => null,
    ]);
    DriverBan::factory()->create([
        'driver_id'   => $driver->id,
        'unbanned_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/bans/active');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.driver_id', $driver->id);
});

it('returns empty list when no active bans', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/bans/active');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(0, 'data');
});

it('rejects unauthenticated request', function (): void {
    $response = $this->getJson('/api/v1/driver/bans/active');

    $response->assertUnauthorized();
});
