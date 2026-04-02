<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;

function createAdminForUserStatus(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('admin can deactivate user', function (): void {
    $admin = createAdminForUserStatus();
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'status'            => UserStatus::ACTIVE,
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'inactive'])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'inactive');

    expect($user->fresh()->status)->toBe(UserStatus::INACTIVE);
});

test('admin can ban user', function (): void {
    $admin = createAdminForUserStatus();
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'status'            => UserStatus::ACTIVE,
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'banned'])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'banned');

    $fresh = $user->fresh();
    expect($fresh->status)->toBe(UserStatus::BANNED)
        ->and($fresh->banned_at)->not->toBeNull();
});

test('admin can reactivate user', function (): void {
    $admin = createAdminForUserStatus();
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'status'            => UserStatus::BANNED,
        'banned_at'         => now(),
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'active'])
        ->assertOk()
        ->assertJsonPath('data.status', 'active');

    $fresh = $user->fresh();
    expect($fresh->status)->toBe(UserStatus::ACTIVE)
        ->and($fresh->banned_at)->toBeNull();
});

test('banned user gets banned_at cleared on reactivation', function (): void {
    $admin = createAdminForUserStatus();
    $user = User::factory()->create([
        'status'    => UserStatus::BANNED,
        'banned_at' => now()->subDay(),
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'inactive']);

    expect($user->fresh()->banned_at)->toBeNull();
});

test('status is required', function (): void {
    $admin = createAdminForUserStatus();
    $user = User::factory()->create();

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$user->id}/status", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

test('status must be valid enum value', function (): void {
    $admin = createAdminForUserStatus();
    $user = User::factory()->create();

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'invalid'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

test('admin cannot change own status', function (): void {
    $admin = createAdminForUserStatus();

    actingAs($admin)
        ->putJson("/api/v1/admin/users/{$admin->id}/status", ['status' => 'inactive'])
        ->assertForbidden();
});

test('non-admin cannot change user status', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
    ]);
    $user = User::factory()->create();

    actingAs($rider)
        ->putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'inactive'])
        ->assertForbidden();
});

test('unauthenticated user cannot change user status', function (): void {
    $user = User::factory()->create();

    putJson("/api/v1/admin/users/{$user->id}/status", ['status' => 'inactive'])
        ->assertUnauthorized();
});
