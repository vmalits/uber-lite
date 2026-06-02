<?php

declare(strict_types=1);

use App\Data\Rider\NearbyDriversRequestData;
use App\Enums\VehicleType;
use App\Models\User;
use App\Models\Vehicle;
use App\Queries\Driver\FindNearbyDriversQueryInterface;
use App\Queries\Rider\GetNearbyDriversQuery;

beforeEach(function (): void {
    $this->findNearbyMock = Mockery::mock(FindNearbyDriversQueryInterface::class);
    $this->query = new GetNearbyDriversQuery($this->findNearbyMock);
});

it('returns enriched driver data from nearby results', function (): void {
    $driver = User::factory()->driver()->create();
    Vehicle::factory()->create([
        'driver_id'    => $driver->id,
        'brand'        => 'Toyota',
        'model'        => 'Camry',
        'year'         => 2022,
        'color'        => 'White',
        'vehicle_type' => VehicleType::SEDAN->value,
    ]);

    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->with(28.8638, 47.0105, 3.0)
        ->andReturn([
            ['driver_id' => $driver->id, 'distance_km' => 1.5],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638);
    $results = $this->query->execute($data);

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($driver->id)
        ->and($results->first()->distance_meters)->toBe(1500)
        ->and($results->first()->estimated_arrival_seconds)->toBe(180)
        ->and($results->first()->vehicle)->not->toBeNull()
        ->and($results->first()->vehicle->brand)->toBe('Toyota')
        ->and($results->first()->vehicle->vehicle_type)->toBe('sedan');
});

it('returns empty collection when no nearby drivers', function (): void {
    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->andReturn([]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638);
    $results = $this->query->execute($data);

    expect($results)->toBeEmpty();
});

it('filters by vehicle type', function (): void {
    $driver1 = User::factory()->driver()->create();
    $driver2 = User::factory()->driver()->create();

    Vehicle::factory()->create([
        'driver_id'    => $driver1->id,
        'vehicle_type' => VehicleType::SEDAN->value,
    ]);
    Vehicle::factory()->create([
        'driver_id'    => $driver2->id,
        'vehicle_type' => VehicleType::SUV->value,
    ]);

    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->andReturn([
            ['driver_id' => $driver1->id, 'distance_km' => 0.5],
            ['driver_id' => $driver2->id, 'distance_km' => 1.0],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638, vehicleType: VehicleType::SUV);
    $results = $this->query->execute($data);

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe($driver2->id)
        ->and($results->first()->vehicle->vehicle_type)->toBe('suv');
});

it('excludes driver when vehicle type filter does not match', function (): void {
    $driver = User::factory()->driver()->create();
    Vehicle::factory()->create([
        'driver_id'    => $driver->id,
        'vehicle_type' => VehicleType::SEDAN->value,
    ]);

    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->andReturn([
            ['driver_id' => $driver->id, 'distance_km' => 0.5],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638, vehicleType: VehicleType::MINIVAN);
    $results = $this->query->execute($data);

    expect($results)->toBeEmpty();
});

it('handles driver without vehicle', function (): void {
    $driver = User::factory()->driver()->create();

    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->andReturn([
            ['driver_id' => $driver->id, 'distance_km' => 2.0],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638);
    $results = $this->query->execute($data);

    expect($results)->toHaveCount(1)
        ->and($results->first()->vehicle)->toBeNull();
});

it('skips driver not found in database', function (): void {
    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->andReturn([
            ['driver_id' => 'nonexistent_id', 'distance_km' => 1.0],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638);
    $results = $this->query->execute($data);

    expect($results)->toBeEmpty();
});

it('converts radius meters to kilometers', function (): void {
    $driver = User::factory()->driver()->create();

    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->with(28.8638, 47.0105, 5.0)
        ->andReturn([
            ['driver_id' => $driver->id, 'distance_km' => 3.0],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638, radiusMeters: 5000);
    $results = $this->query->execute($data);

    expect($results)->toHaveCount(1);
});

it('calculates eta based on 30 km/h average speed', function (): void {
    $driver = User::factory()->driver()->create();

    $this->findNearbyMock
        ->shouldReceive('execute')
        ->once()
        ->andReturn([
            ['driver_id' => $driver->id, 'distance_km' => 5.0],
        ]);

    $data = new NearbyDriversRequestData(lat: 47.0105, lng: 28.8638);
    $results = $this->query->execute($data);

    expect($results->first()->distance_meters)->toBe(5000)
        ->and($results->first()->estimated_arrival_seconds)->toBe(600);
});

afterEach(function (): void {
    Mockery::close();
});
