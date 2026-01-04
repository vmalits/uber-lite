<?php

declare(strict_types=1);

use App\Enums\BanSource;
use App\Enums\DriverBanType;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\DriverBan;
use App\Models\User;

it('bans a driver successfully', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();

    $payload = [
        'reason'     => 'Violation of rules',
        'ban_type'   => DriverBanType::TEMPORARY->value,
        'ban_source' => BanSource::ADMIN->value,
    ];

    $response = $this->actingAs($admin)->postJson("/api/v1/admin/drivers/{$driver->id}/ban", $payload);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'data' => [
                'id', 'driver_id', 'ban_type', 'ban_source', 'reason', 'expires_at', 'created_at', 'updated_at',
            ],
        ])
        ->assertJson([
            'message' => 'Driver banned successfully.',
            'data'    => [
                'driver_id'  => $driver->id,
                'ban_type'   => DriverBanType::TEMPORARY->value,
                'ban_source' => BanSource::ADMIN->value,
                'reason'     => 'Violation of rules',
            ],
        ]);

    $this->assertDatabaseHas('driver_bans', [
        'driver_id' => $driver->id,
        'reason'    => 'Violation of rules',
    ]);
    $this->assertDatabaseHas('users', [
        'id'     => $driver->id,
        'status' => UserStatus::BANNED->value,
    ]);
    $this->assertNotNull(User::find($driver->id)->banned_at);
});

it('returns validation error for missing fields', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();

    $response = $this->actingAs($admin)->postJson("/api/v1/admin/drivers/{$driver->id}/ban", []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['reason', 'ban_type', 'ban_source']);
});

it('bans a driver with explicit expires_at', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();
    $expiresAt = now()->addDays(3)->format('Y-m-d H:i:s');
    $payload = [
        'reason'     => 'Test explicit expires',
        'ban_type'   => DriverBanType::TEMPORARY->value,
        'ban_source' => BanSource::ADMIN->value,
        'expires_at' => $expiresAt,
    ];
    $response = $this->actingAs($admin)->postJson("/api/v1/admin/drivers/{$driver->id}/ban", $payload);
    $response->assertOk();
    $this->assertDatabaseHas('driver_bans', [
        'driver_id'  => $driver->id,
        'reason'     => 'Test explicit expires',
        'expires_at' => $expiresAt,
    ]);
    $this->assertDatabaseHas('users', [
        'id'     => $driver->id,
        'status' => UserStatus::BANNED->value,
    ]);
    $this->assertNotNull(User::find($driver->id)->banned_at);
});

it('bans a driver permanently (no expires_at)', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();
    $payload = [
        'reason'     => 'Permanent ban',
        'ban_type'   => DriverBanType::PERMANENT->value,
        'ban_source' => BanSource::ADMIN->value,
    ];
    $response = $this->actingAs($admin)->postJson("/api/v1/admin/drivers/{$driver->id}/ban", $payload);
    $response->assertOk();
    $this->assertDatabaseHas('driver_bans', [
        'driver_id'  => $driver->id,
        'reason'     => 'Permanent ban',
        'ban_type'   => DriverBanType::PERMANENT->value,
        'expires_at' => null,
    ]);
    $this->assertDatabaseHas('users', [
        'id'     => $driver->id,
        'status' => UserStatus::BANNED->value,
    ]);
    $this->assertNotNull(User::find($driver->id)->banned_at);
});

it('returns error if driver is already banned', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();
    DriverBan::factory()->create([
        'driver_id'   => $driver->id,
        'ban_type'    => DriverBanType::TEMPORARY,
        'ban_source'  => BanSource::ADMIN,
        'reason'      => 'Already banned',
        'unbanned_at' => null,
    ]);
    $payload = [
        'reason'     => 'Try ban again',
        'ban_type'   => DriverBanType::TEMPORARY->value,
        'ban_source' => BanSource::ADMIN->value,
    ];
    $response = $this->actingAs($admin)->postJson("/api/v1/admin/drivers/{$driver->id}/ban", $payload);
    $response->assertStatus(409);
    $response->assertJsonFragment(['message' => 'Driver with id ['.$driver->id.'] is already banned.']);
});

it('returns 404 for non-existent driver', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $payload = [
        'reason'     => 'Ban ghost',
        'ban_type'   => DriverBanType::TEMPORARY->value,
        'ban_source' => BanSource::ADMIN->value,
    ];
    $response = $this->actingAs($admin)->postJson('/api/v1/admin/drivers/ghost-id/ban', $payload);
    $response->assertNotFound();
});

it('returns validation error for invalid ban_type and ban_source', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();
    $payload = [
        'reason'     => 'Invalid enums',
        'ban_type'   => 'not_a_type',
        'ban_source' => 'not_a_source',
    ];
    $response = $this->actingAs($admin)->postJson("/api/v1/admin/drivers/{$driver->id}/ban", $payload);
    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['ban_type', 'ban_source']);
});
