<?php

declare(strict_types=1);

use App\Enums\DriverAvailabilityStatus;
use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Laravel\Sanctum\Sanctum;

/**
 * @property User $rider
 * @property User $driver
 */
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

test('rider can track ride with driver location', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'        => $this->rider->id,
        'driver_id'       => $this->driver->id,
        'status'          => RideStatus::ON_THE_WAY,
        'origin_lat'      => 55.7558,
        'origin_lng'      => 37.6173,
        'destination_lat' => 55.7522,
        'destination_lng' => 37.6156,
    ]);

    // Mock Redis for driver location
    $redis = app(RedisFactory::class);
    $redis->connection()->command('set', [
        "driver:{$this->driver->id}:location",
        json_encode([
            'status' => DriverAvailabilityStatus::BUSY->value,
            'lat'    => 55.7560,
            'lng'    => 37.6180,
            'ts'     => time(),
        ]),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => [
                'ride_id'         => $ride->id,
                'ride_status'     => RideStatus::ON_THE_WAY->value,
                'driver_id'       => $this->driver->id,
                'origin_lat'      => 55.7558,
                'origin_lng'      => 37.6173,
                'destination_lat' => 55.7522,
                'destination_lng' => 37.6156,
            ],
        ])
        ->assertJsonStructure([
            'data' => [
                'driver_location' => [
                    'driver_id',
                    'status',
                    'lat',
                    'lng',
                    'ts',
                ],
            ],
        ]);
});

test('rider can track ride without driver location when not in redis', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => [
                'ride_id'         => $ride->id,
                'ride_status'     => RideStatus::ACCEPTED->value,
                'driver_id'       => $this->driver->id,
                'driver_location' => null,
            ],
        ]);
});

test('rider cannot track ride without driver assigned', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => null,
        'status'    => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertForbidden()
        ->assertJsonPath('message', 'This action is unauthorized.');
});

test('rider cannot track completed ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertForbidden()
        ->assertJsonPath('message', 'This action is unauthorized.');
});

test('rider cannot track cancelled ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'driver_id'    => $this->driver->id,
        'status'       => RideStatus::CANCELLED,
        'cancelled_at' => now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertForbidden();
});

test('rider cannot track another rider ride', function (): void {
    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $otherRider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertForbidden()
        ->assertJsonPath('message', 'This action is unauthorized.');
});

test('guest cannot track ride', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'  => $this->rider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertUnauthorized();
});

test('rider with incomplete profile cannot track ride', function (): void {
    $incompleteRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $incompleteRider->id,
        'driver_id' => $this->driver->id,
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($incompleteRider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertForbidden();
});

test('returns 404 for non-existent ride', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/rides/01jk9v6v9v6v9v6v9v6v9v6v9v/track');

    $response->assertNotFound();
});

test('can track ride in started status', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'   => $this->rider->id,
        'driver_id'  => $this->driver->id,
        'status'     => RideStatus::STARTED,
        'started_at' => now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertOk()
        ->assertJsonPath('data.ride_status', RideStatus::STARTED->value);
});

test('can track ride in arrived status', function (): void {
    $ride = Ride::factory()->create([
        'rider_id'   => $this->rider->id,
        'driver_id'  => $this->driver->id,
        'status'     => RideStatus::ARRIVED,
        'arrived_at' => now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/track");

    $response->assertOk()
        ->assertJsonPath('data.ride_status', RideStatus::ARRIVED->value);
});
