<?php

declare(strict_types=1);

use App\Data\Driver\DriverRealtimeLocationData;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetRideTrackingQuery;
use App\Services\Driver\DriverLocationConfig;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

beforeEach(function (): void {
    $this->redis = Mockery::mock(RedisFactory::class);
    $this->config = app(DriverLocationConfig::class);
    $this->query = new GetRideTrackingQuery($this->redis, $this->config);
});

it('returns tracking data with driver location', function (): void {
    $driver = User::factory()->create();
    $ride = Ride::factory()->create([
        'driver_id'       => $driver->id,
        'status'          => RideStatus::ON_THE_WAY,
        'origin_lat'      => 55.7558,
        'origin_lng'      => 37.6173,
        'destination_lat' => 55.7522,
        'destination_lng' => 37.6156,
    ]);

    $locationKey = $this->config->locationKey($driver->id);

    $redisConnection = Mockery::mock();
    $redisConnection
        ->shouldReceive('command')
        ->with('get', [$locationKey])
        ->once()
        ->andReturn(json_encode([
            'status' => 'busy',
            'lat'    => 55.7560,
            'lng'    => 37.6180,
            'ts'     => 1708123456,
        ]));

    $this->redis
        ->shouldReceive('connection')
        ->once()
        ->andReturn($redisConnection);

    $result = $this->query->execute($ride);

    expect($result->ride_id)->toBe($ride->id)
        ->and($result->ride_status)->toBe(RideStatus::ON_THE_WAY)
        ->and($result->driver_id)->toBe($driver->id)
        ->and($result->driver_location)->toBeInstanceOf(DriverRealtimeLocationData::class)
        ->and($result->driver_location->lat)->toBe(55.7560)
        ->and($result->driver_location->lng)->toBe(37.6180)
        ->and($result->origin_lat)->toBe(55.7558)
        ->and($result->origin_lng)->toBe(37.6173)
        ->and($result->destination_lat)->toBe(55.7522)
        ->and($result->destination_lng)->toBe(37.6156);
});

it('returns null driver location when not found in redis', function (): void {
    $driver = User::factory()->create();
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    $locationKey = $this->config->locationKey($driver->id);

    $redisConnection = Mockery::mock();
    $redisConnection
        ->shouldReceive('command')
        ->with('get', [$locationKey])
        ->once()
        ->andReturn(false);

    $this->redis
        ->shouldReceive('connection')
        ->once()
        ->andReturn($redisConnection);

    $result = $this->query->execute($ride);

    expect($result->driver_location)->toBeNull();
});

it('returns null driver location when no driver assigned', function (): void {
    $ride = Ride::factory()->create([
        'driver_id' => null,
        'status'    => RideStatus::PENDING,
    ]);

    $this->redis->shouldNotReceive('connection');

    $result = $this->query->execute($ride);

    expect($result->driver_id)->toBeNull()
        ->and($result->driver_location)->toBeNull();
});

it('returns correct ride coordinates', function (): void {
    $ride = Ride::factory()->create([
        'driver_id'       => null,
        'origin_lat'      => 47.0205,
        'origin_lng'      => 28.8315,
        'destination_lat' => 46.9753,
        'destination_lng' => 28.8574,
    ]);

    $result = $this->query->execute($ride);

    expect($result->origin_lat)->toBe(47.0205)
        ->and($result->origin_lng)->toBe(28.8315)
        ->and($result->destination_lat)->toBe(46.9753)
        ->and($result->destination_lng)->toBe(28.8574);
});

afterEach(function (): void {
    Mockery::close();
});
