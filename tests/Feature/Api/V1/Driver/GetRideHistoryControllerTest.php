<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can get their ride history', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create some rides for this driver
    Ride::factory()->count(3)->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    // Create a ride for another driver
    /** @var User $otherDriver */
    $otherDriver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $otherDriver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonCount(3, 'data.items')
        ->assertJsonPath('data.pagination.total', 3);
});

test('driver history is paginated', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->count(20)->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data.items')
        ->assertJsonPath('data.pagination.total', 20)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.last_page', 2);
});

test('guest cannot get driver ride history', function (): void {
    $response = $this->getJson('/api/v1/driver/rides/history');

    $response->assertUnauthorized();
});

test('driver with incomplete profile cannot get ride history', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history');

    $response->assertForbidden();
});

test('rider cannot get driver ride history', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson('/api/v1/driver/rides/history');

    $response->assertForbidden();
});

test('driver can filter ride history by status', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history?filter[status]=completed');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.status', RideStatus::COMPLETED->value);
});

test('driver can filter ride history by cancelled status', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history?filter[status]=cancelled');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.status', RideStatus::CANCELLED->value);
});

test('driver filtering by invalid status returns all history', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history?filter[status]=pending');

    $response->assertOk()
        ->assertJsonCount(2, 'data.items');
});

test('driver can sort ride history by price', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id'  => $driver->id,
        'rider_id'   => $rider->id,
        'status'     => RideStatus::COMPLETED,
        'price'      => 100.0,
        'created_at' => now()->subDay(),
    ]);

    Ride::factory()->create([
        'driver_id'  => $driver->id,
        'rider_id'   => $rider->id,
        'status'     => RideStatus::COMPLETED,
        'price'      => 200.0,
        'created_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    // Sort by price ascending
    $response = $this->getJson('/api/v1/driver/rides/history?sort=price');

    $response->assertOk()
        ->assertJsonPath('data.items.0.price', 100)
        ->assertJsonPath('data.items.1.price', 200);

    // Sort by price descending
    $response = $this->getJson('/api/v1/driver/rides/history?sort=-price');

    $response->assertOk()
        ->assertJsonPath('data.items.0.price', 200)
        ->assertJsonPath('data.items.1.price', 100);
});

test('driver history only includes completed and cancelled rides', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::PENDING,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::STARTED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/rides/history');

    $response->assertOk()
        ->assertJsonCount(2, 'data.items')
        ->assertJsonPath('data.pagination.total', 2);
});

test('driver history only includes their own rides', function (): void {
    /** @var User $driver1 */
    $driver1 = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $driver2 */
    $driver2 = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides for both drivers
    Ride::factory()->create([
        'driver_id' => $driver1->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver2->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver1);

    $response = $this->getJson('/api/v1/driver/rides/history');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.pagination.total', 1);
});
