<?php

declare(strict_types=1);

use App\Data\Rider\NearbyDriverData;
use App\Data\Rider\NearbyDriversRequestData;
use App\Data\Rider\NearbyDriverVehicleData;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\VehicleType;
use App\Models\User;
use App\Queries\Rider\GetNearbyDriversQueryInterface;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
});

test('can get nearby drivers', function (): void {
    $driverData = new NearbyDriverData(
        id: '01ABC123',
        distance_meters: 500,
        estimated_arrival_seconds: 60,
        vehicle: new NearbyDriverVehicleData(
            brand: 'Toyota',
            model: 'Camry',
            year: 2022,
            color: 'White',
            vehicle_type: 'sedan',
        ),
        driver_rating: 4.5,
    );

    $mock = Mockery::mock(GetNearbyDriversQueryInterface::class);
    $mock->shouldReceive('execute')
        ->once()
        ->andReturn(new Collection([$driverData]));

    app()->instance(GetNearbyDriversQueryInterface::class, $mock);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 47.0105,
        'lng' => 28.8638,
    ]));

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.total_count', 1)
        ->assertJsonPath('data.radius_meters', 3000)
        ->assertJsonPath('data.search_center.lat', 47.0105)
        ->assertJsonPath('data.search_center.lng', 28.8638)
        ->assertJsonPath('data.average_eta_seconds', 60)
        ->assertJsonStructure([
            'success',
            'data' => [
                'drivers' => [
                    '*' => ['id', 'distance_meters', 'estimated_arrival_seconds', 'vehicle', 'driver_rating'],
                ],
                'total_count',
                'search_center' => ['lat', 'lng'],
                'radius_meters',
                'average_eta_seconds',
            ],
        ]);
});

test('returns empty list when no drivers nearby', function (): void {
    $mock = Mockery::mock(GetNearbyDriversQueryInterface::class);
    $mock->shouldReceive('execute')
        ->once()
        ->andReturn(new Collection);

    app()->instance(GetNearbyDriversQueryInterface::class, $mock);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 47.0105,
        'lng' => 28.8638,
    ]));

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.drivers', [])
        ->assertJsonPath('data.total_count', 0)
        ->assertJsonPath('data.average_eta_seconds', null);
});

test('passes radius and vehicle type to query', function (): void {
    $mock = Mockery::mock(GetNearbyDriversQueryInterface::class);
    $mock->shouldReceive('execute')
        ->once()
        ->withArgs(function (NearbyDriversRequestData $data): bool {
            expect($data->lat)->toBe(47.0105)
                ->and($data->lng)->toBe(28.8638)
                ->and($data->radiusMeters)->toBe(5000)
                ->and($data->vehicleType)->toBe(VehicleType::SEDAN);

            return true;
        })
        ->andReturn(new Collection);

    app()->instance(GetNearbyDriversQueryInterface::class, $mock);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat'          => 47.0105,
        'lng'          => 28.8638,
        'radius'       => 5000,
        'vehicle_type' => 'sedan',
    ]));

    $response->assertOk()
        ->assertJsonPath('data.radius_meters', 5000);
});

test('calculates average eta across multiple drivers', function (): void {
    $drivers = new Collection([
        new NearbyDriverData('id1', 500, 60, null, null),
        new NearbyDriverData('id2', 1000, 120, null, null),
    ]);

    $mock = Mockery::mock(GetNearbyDriversQueryInterface::class);
    $mock->shouldReceive('execute')
        ->once()
        ->andReturn($drivers);

    app()->instance(GetNearbyDriversQueryInterface::class, $mock);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 47.0105,
        'lng' => 28.8638,
    ]));

    $response->assertOk()
        ->assertJsonPath('data.average_eta_seconds', 90);
});

test('unauthenticated user gets 401', function (): void {
    $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 47.0105,
        'lng' => 28.8638,
    ]))->assertUnauthorized();
});

test('driver cannot access rider nearby drivers', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 47.0105,
        'lng' => 28.8638,
    ]))->assertForbidden();
});

test('validates required lat and lng', function (): void {
    Sanctum::actingAs($this->rider);

    $this->getJson(route('api.v1.rider.nearby-drivers'))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['lat', 'lng']);
});

test('validates lat range', function (): void {
    Sanctum::actingAs($this->rider);

    $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 100,
        'lng' => 28.8638,
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors(['lat']);
});

test('validates lng range', function (): void {
    Sanctum::actingAs($this->rider);

    $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat' => 47.0105,
        'lng' => 200,
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors(['lng']);
});

test('validates radius bounds', function (): void {
    Sanctum::actingAs($this->rider);

    $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat'    => 47.0105,
        'lng'    => 28.8638,
        'radius' => 50000,
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors(['radius']);
});

test('validates vehicle type must be valid enum', function (): void {
    Sanctum::actingAs($this->rider);

    $this->getJson(route('api.v1.rider.nearby-drivers', [
        'lat'          => 47.0105,
        'lng'          => 28.8638,
        'vehicle_type' => 'helicopter',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors(['vehicle_type']);
});

afterEach(function (): void {
    Mockery::close();
});
