<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('selects role successfully and returns next_action add_email', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37361111111',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => null,
    ]);

    Sanctum::actingAs($user);

    $resp = $this->postJson('/api/v1/auth/select-role', [
        'role' => UserRole::RIDER->value,
    ]);

    $resp->assertOk()
        ->assertJsonPath('data.role', UserRole::RIDER->value)
        ->assertJsonPath('data.profile_step', ProfileStep::PHONE_VERIFIED->value)
        ->assertJsonPath('meta.next_action', 'add_email');

    $user->refresh();
    expect($user->role)->toBe(UserRole::RIDER);
});

it('validates role must be in allowed list', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37362222222',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => null,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/select-role', [
        'role' => 'invalid-role',
    ])->assertUnprocessable()->assertJsonValidationErrors(['role']);
});

it('returns 422 when phone is not verified', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37363333333',
        'phone_verified_at' => null,
        'profile_step'      => null,
        'role'              => null,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/select-role', [
        'role' => UserRole::DRIVER->value,
    ])->assertUnprocessable()->assertJsonValidationErrors(['phone']);
});

it('returns 422 when role already selected', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone'             => '+37364444444',
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => UserRole::RIDER,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/auth/select-role', [
        'role' => UserRole::DRIVER->value,
    ])->assertUnprocessable()->assertJsonValidationErrors(['role']);
});

it('returns 401 when unauthenticated', function (): void {
    $this->postJson('/api/v1/auth/select-role', [
        'role' => UserRole::RIDER->value,
    ])->assertUnauthorized();
});
