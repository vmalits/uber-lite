<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to delete a user', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user = User::factory()->create(['role' => UserRole::RIDER]);

    $response = $this->actingAs($admin, 'sanctum')
        ->deleteJson("/api/v1/admin/users/{$user->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

it('prevents admin from deleting themselves', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin, 'sanctum')
        ->deleteJson("/api/v1/admin/users/{$admin->id}");

    $response->assertForbidden();
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

it('prevents non-admin from deleting a user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $user = User::factory()->create(['role' => UserRole::DRIVER]);

    $response = $this->actingAs($rider, 'sanctum')
        ->deleteJson("/api/v1/admin/users/{$user->id}");

    $response->assertForbidden();
    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

it('returns 404 for non-existent user', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin, 'sanctum')
        ->deleteJson('/api/v1/admin/users/non-existent-id');

    $response->assertNotFound();
});

it('requires authentication to delete a user', function () {
    $user = User::factory()->create(['role' => UserRole::RIDER]);

    $response = $this->deleteJson("/api/v1/admin/users/{$user->id}");

    $response->assertUnauthorized();
    $this->assertDatabaseHas('users', ['id' => $user->id]);
});
