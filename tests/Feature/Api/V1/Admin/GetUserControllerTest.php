<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns user details for admin', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    $user = User::factory()->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/users/{$user->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'first_name',
                'last_name',
                'phone',
                'email',
                'avatar_urls',
                'role',
                'locale',
                'profile_step',
                'status',
                'phone_verified_at',
                'email_verified_at',
                'last_login_at',
                'banned_at',
                'created_at' => ['human', 'string'],
                'updated_at' => ['human', 'string'],
            ],
        ]);

    expect($response['data']['id'])->toBe($user->id);
});

it('returns 404 for non-existent user', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users/non-existent-id');

    $response->assertNotFound();
});

it('rejects unauthorized request without token', function () {
    $user = User::factory()->create();
    $response = $this->getJson("/api/v1/admin/users/{$user->id}");
    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $user = User::factory()->create();
    $response = $this->actingAs($rider, 'sanctum')
        ->getJson("/api/v1/admin/users/{$user->id}");
    $response->assertForbidden();
});
