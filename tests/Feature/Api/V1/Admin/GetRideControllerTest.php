<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns ride details for admin', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $ride = Ride::factory()->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/rides/{$ride->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'rider_id',
                'driver_id',
                'origin_address',
                'status',
                'price',
                'created_at',
                'updated_at',
            ],
        ]);

    expect($response['data']['id'])->toBe($ride->id);
});

it('returns 404 for non-existent ride', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/rides/non-existent-id');

    $response->assertNotFound();
});

it('rejects unauthorized request', function () {
    $ride = Ride::factory()->create();

    $response = $this->getJson("/api/v1/admin/rides/{$ride->id}");

    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $ride = Ride::factory()->create();

    $response = $this->actingAs($rider, 'sanctum')
        ->getJson("/api/v1/admin/rides/{$ride->id}");

    $response->assertForbidden();
});
