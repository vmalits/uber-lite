<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverBan;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns ban details for driver', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ban = DriverBan::factory()->create([
        'driver_id' => $driver->id,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson("/api/v1/driver/bans/{$ban->id}");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $ban->id)
        ->assertJsonPath('data.driver_id', $driver->id);
});

it('rejects access to another driver ban', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $otherDriver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ban = DriverBan::factory()->create([
        'driver_id' => $otherDriver->id,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson("/api/v1/driver/bans/{$ban->id}");

    $response->assertForbidden();
});

it('returns 404 for non-existent ban', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/bans/non-existent-id');

    $response->assertNotFound();
});

it('rejects unauthenticated request', function (): void {
    $ban = DriverBan::factory()->create();

    $response = $this->getJson("/api/v1/driver/bans/{$ban->id}");

    $response->assertUnauthorized();
});
