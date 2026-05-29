<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;

function createAdminForDriverRides(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('admin can list driver rides', function (): void {
    $admin = createAdminForDriverRides();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    Ride::factory()->count(3)->create(['driver_id' => $driver->id]);
    Ride::factory()->count(2)->create();

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/rides")
        ->assertOk()
        ->assertJsonCount(3, 'data.items');
});

test('admin can filter driver rides by status', function (): void {
    $admin = createAdminForDriverRides();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    Ride::factory()->count(2)->create(['driver_id' => $driver->id, 'status' => RideStatus::COMPLETED]);
    Ride::factory()->create(['driver_id' => $driver->id, 'status' => RideStatus::CANCELLED]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/rides?filter[status]=completed")
        ->assertOk()
        ->assertJsonCount(2, 'data.items');
});

test('admin can sort driver rides', function (): void {
    $admin = createAdminForDriverRides();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    Ride::factory()->create(['driver_id' => $driver->id, 'price' => 1000, 'status' => RideStatus::COMPLETED]);
    Ride::factory()->create(['driver_id' => $driver->id, 'price' => 500, 'status' => RideStatus::COMPLETED]);

    $response = actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/rides?sort=price")
        ->assertOk();

    $items = $response->json('data.items');
    expect($items[0]['price'])->toBeLessThan($items[1]['price']);
});

test('admin driver rides returns empty for driver with no rides', function (): void {
    $admin = createAdminForDriverRides();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/rides")
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('non-admin cannot list driver rides', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    actingAs($rider)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/rides")
        ->assertForbidden();
});

test('unauthenticated user cannot list driver rides', function (): void {
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    \Pest\Laravel\getJson("/api/v1/admin/drivers/{$driver->id}/rides")
        ->assertUnauthorized();
});

test('driver rides response includes ride data structure', function (): void {
    $admin = createAdminForDriverRides();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    Ride::factory()->create(['driver_id' => $driver->id, 'status' => RideStatus::COMPLETED]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/rides")
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => ['id', 'rider_id', 'driver_id', 'status', 'price', 'created_at'],
                ],
            ],
        ]);
});
