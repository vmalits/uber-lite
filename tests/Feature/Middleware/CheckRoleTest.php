<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Route::get('/test-role-rider', function () {
        return response()->json(['message' => 'Rider access granted']);
    })->middleware(['auth:sanctum', 'role:rider']);

    Route::get('/test-role-driver', function () {
        return response()->json(['message' => 'Driver access granted']);
    })->middleware(['auth:sanctum', 'role:driver']);
});

it('allows access to rider when role is rider', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['role' => UserRole::RIDER]);
    Sanctum::actingAs($user);

    $this->getJson('/test-role-rider')
        ->assertOk()
        ->assertJson(['message' => 'Rider access granted']);
});

it('denies access to rider when role is driver', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['role' => UserRole::DRIVER]);
    Sanctum::actingAs($user);

    $this->getJson('/test-role-rider')
        ->assertForbidden();
});

it('allows access to driver when role is driver', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['role' => UserRole::DRIVER]);
    Sanctum::actingAs($user);

    $this->getJson('/test-role-driver')
        ->assertOk()
        ->assertJson(['message' => 'Driver access granted']);
});

it('denies access to driver when role is rider', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['role' => UserRole::RIDER]);
    Sanctum::actingAs($user);

    $this->getJson('/test-role-driver')
        ->assertForbidden();
});

it('denies access when user has no role', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['role' => null]);
    Sanctum::actingAs($user);

    $this->getJson('/test-role-rider')
        ->assertForbidden();
});

it('returns 401 when unauthenticated', function (): void {
    $this->getJson('/test-role-rider')
        ->assertUnauthorized();
});
