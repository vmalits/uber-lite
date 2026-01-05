<?php

declare(strict_types=1);

use App\Enums\BanSource;
use App\Enums\DriverBanType;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\DriverBan;
use App\Models\User;

it('successfully unbans a banned driver', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create(['status' => UserStatus::BANNED]);

    $ban = DriverBan::factory()->create([
        'driver_id'   => $driver->id,
        'ban_type'    => DriverBanType::TEMPORARY,
        'ban_source'  => BanSource::ADMIN,
        'reason'      => 'Speeding violation',
        'unbanned_at' => null,
    ]);

    $payload = [
        'reason' => 'Ban lifted after successful appeal and document review',
    ];

    $response = $this->actingAs($admin, 'sanctum')
        ->postJson(route('api.v1.admin.drivers.unban', $driver), $payload);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Driver has been successfully unbanned.')
        ->assertJsonPath('data.unban_reason', $payload['reason'])
        ->assertJsonPath('data.unbanned_by', $admin->id)
        ->assertJsonPath('data.unbanned_at.string', fn ($value) => now()->diffInSeconds($value) < 10);

    $this->assertDatabaseHas('driver_bans', [
        'id'           => $ban->id,
        'unbanned_by'  => $admin->id,
        'unban_reason' => $payload['reason'],
    ]);

    $ban->refresh();
    $this->assertNotNull($ban->unbanned_at);

    $this->assertDatabaseHas('users', [
        'id'        => $driver->id,
        'status'    => UserStatus::ACTIVE->value,
        'banned_at' => null,
    ]);
});

it('returns 409 when trying to unban non-banned driver', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create(['status' => UserStatus::ACTIVE]);

    $this->actingAs($admin)
        ->postJson(route('api.v1.admin.drivers.unban', $driver))
        ->assertConflict()
        ->assertJsonFragment([
            'message' => "Driver with id [{$driver->id}] is not banned.",
        ]);
});

it('requires admin role to unban', function () {
    $regularUser = User::factory()->create(['role' => UserRole::DRIVER]);
    $bannedDriver = User::factory()->create(['status' => UserStatus::BANNED]);

    $this->actingAs($regularUser)
        ->postJson(route('api.v1.admin.drivers.unban', $bannedDriver))
        ->assertForbidden();
});

it('rejects too long reason', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create(['status' => UserStatus::BANNED]);

    DriverBan::factory()->create(['driver_id' => $driver->id]);

    $this->actingAs($admin)
        ->postJson(route('api.v1.admin.drivers.unban', $driver), [
            'reason' => str_repeat('a', 501),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('reason');
});
