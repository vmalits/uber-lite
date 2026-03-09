<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('rider can get driver reviews', function (): void {
    $ride = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride->id,
        'rider_id' => $this->rider->id,
        'rating'   => 5,
        'comment'  => 'Great driver!',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'rating',
                        'comment',
                        'rider_name',
                        'created_at',
                    ],
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.pagination.total', 1)
        ->assertJsonPath('data.items.0.rating', 5)
        ->assertJsonPath('data.items.0.comment', 'Great driver!');
});

test('driver reviews only include completed rides', function (): void {
    $completedRide = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $cancelledRide = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::CANCELLED,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $completedRide->id,
        'rider_id' => $this->rider->id,
        'rating'   => 5,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $cancelledRide->id,
        'rider_id' => $this->rider->id,
        'rating'   => 1,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews");

    $response->assertOk()
        ->assertJsonPath('data.pagination.total', 1)
        ->assertJsonPath('data.items.0.rating', 5);
});

test('driver reviews can be sorted by rating', function (): void {
    $ride1 = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride1->id,
        'rider_id' => $this->rider->id,
        'rating'   => 5,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride2->id,
        'rider_id' => $this->rider->id,
        'rating'   => 3,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews?sort=rating");

    $response->assertOk()
        ->assertJsonPath('data.items.0.rating', 3)
        ->assertJsonPath('data.items.1.rating', 5);
});

test('driver reviews can be sorted by rating descending', function (): void {
    $ride1 = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride1->id,
        'rider_id' => $this->rider->id,
        'rating'   => 3,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride2->id,
        'rider_id' => $this->rider->id,
        'rating'   => 5,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews?sort=-rating");

    $response->assertOk()
        ->assertJsonPath('data.items.0.rating', 5)
        ->assertJsonPath('data.items.1.rating', 3);
});

test('driver reviews are paginated', function (): void {
    foreach (range(1, 20) as $i) {
        $ride = Ride::factory()->create([
            'driver_id' => $this->driver->id,
            'rider_id'  => $this->rider->id,
            'status'    => RideStatus::COMPLETED,
        ]);

        RideRating::factory()->create([
            'ride_id'  => $ride->id,
            'rider_id' => $this->rider->id,
            'rating'   => random_int(1, 5),
        ]);
    }

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews?per_page=5");

    $response->assertOk()
        ->assertJsonPath('data.pagination.per_page', 5)
        ->assertJsonPath('data.pagination.total', 20)
        ->assertJsonPath('data.pagination.last_page', 4)
        ->assertJsonCount(5, 'data.items');
});

test('driver with no reviews returns empty list', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.pagination.total', 0)
        ->assertJsonCount(0, 'data.items');
});

test('guest cannot get driver reviews', function (): void {
    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews");

    $response->assertUnauthorized();
});

test('driver cannot access rider endpoint', function (): void {
    Sanctum::actingAs($this->driver);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews");

    $response->assertForbidden();
});

test('non-existent driver returns 404', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/drivers/non-existent-id/reviews');

    $response->assertNotFound();
});

test('reviews only include ratings for specified driver', function (): void {
    $anotherDriver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $this->driver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $anotherDriver->id,
        'rider_id'  => $this->rider->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride1->id,
        'rider_id' => $this->rider->id,
        'rating'   => 5,
    ]);

    RideRating::factory()->create([
        'ride_id'  => $ride2->id,
        'rider_id' => $this->rider->id,
        'rating'   => 3,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/drivers/{$this->driver->id}/reviews");

    $response->assertOk()
        ->assertJsonPath('data.pagination.total', 1)
        ->assertJsonPath('data.items.0.rating', 5);
});
