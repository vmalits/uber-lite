<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\DriverDocument;
use App\Models\User;

it('returns paginated documents for a driver', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();

    DriverDocument::factory()->count(3)->create([
        'driver_id' => $driver->id,
    ]);

    $response = $this->actingAs($admin)->getJson("/api/v1/admin/drivers/{$driver->id}/documents");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'type',
                        'file_path',
                        'original_name',
                        'mime_type',
                        'size',
                        'status',
                        'rejection_reason',
                        'verified_by',
                        'verified_at',
                        'expires_at',
                        'created_at' => ['human', 'string'],
                        'updated_at' => ['human', 'string'],
                    ],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ],
        ]);

    expect($response->json('data.items'))->toHaveCount(3);
});

it('returns empty list when driver has no documents', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();

    $response = $this->actingAs($admin)->getJson("/api/v1/admin/drivers/{$driver->id}/documents");

    $response->assertOk();
    expect($response->json('data.items'))->toHaveCount(0);
});

it('returns 404 for non-existent driver', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin)->getJson('/api/v1/admin/drivers/non-existent-id/documents');

    $response->assertNotFound();
});

it('returns forbidden for non-admin user', function () {
    $driver = User::factory()->create();

    $response = $this->actingAs($driver)->getJson("/api/v1/admin/drivers/{$driver->id}/documents");

    $response->assertForbidden();
});

it('returns unauthorized without authentication', function () {
    $driver = User::factory()->create();

    $response = $this->getJson("/api/v1/admin/drivers/{$driver->id}/documents");

    $response->assertUnauthorized();
});
