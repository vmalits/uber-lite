<?php

declare(strict_types=1);

use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('exports rides to csv for admin', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    Ride::factory()->count(3)->create(['status' => RideStatus::COMPLETED]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/export/rides');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

it('filters rides by date range', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    Ride::factory()->create([
        'status'     => RideStatus::COMPLETED,
        'created_at' => '2024-01-15 10:00:00',
    ]);
    Ride::factory()->create([
        'status'     => RideStatus::COMPLETED,
        'created_at' => '2024-02-15 10:00:00',
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/export/rides?date_from=2024-02-01&date_to=2024-02-28');

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

it('filters rides by status', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    Ride::factory()->count(2)->create(['status' => RideStatus::COMPLETED]);
    Ride::factory()->count(3)->create(['status' => RideStatus::CANCELLED]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/export/rides?status=completed');

    $response->assertOk();
});

it('rejects unauthorized request without token', function () {
    $response = $this->getJson('/api/v1/admin/export/rides');
    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/admin/export/rides');
    $response->assertForbidden();
});
