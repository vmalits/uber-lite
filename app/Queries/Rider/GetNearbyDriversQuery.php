<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\NearbyDriverData;
use App\Data\Rider\NearbyDriversRequestData;
use App\Data\Rider\NearbyDriverVehicleData;
use App\Models\User;
use App\Models\Vehicle;
use App\Queries\Driver\FindNearbyDriversQueryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class GetNearbyDriversQuery implements GetNearbyDriversQueryInterface
{
    private const float METERS_PER_KILOMETER = 1000.0;

    private const int SECONDS_PER_HOUR = 3600;

    private const int AVG_SPEED_KMH = 30;

    public function __construct(
        private FindNearbyDriversQueryInterface $findNearbyDrivers,
    ) {}

    public function execute(NearbyDriversRequestData $data): Collection
    {
        $radiusKm = $data->radiusMeters / self::METERS_PER_KILOMETER;

        $nearby = $this->findNearbyDrivers->execute(
            lng: $data->lng,
            lat: $data->lat,
            radiusKm: $radiusKm,
        );

        if ($nearby === []) {
            return new Collection;
        }

        $distances = $this->buildDistanceMap($nearby);
        $driverIds = array_keys($distances);

        $drivers = $this->loadDriversWithRating($driverIds);

        $results = new Collection;

        foreach ($driverIds as $driverId) {
            $driver = $drivers->get($driverId);

            if ($driver === null) {
                continue;
            }

            $distanceKm = $distances[$driverId];
            $vehicle = $this->resolveVehicle($driver->vehicles, $data->vehicleType);

            if ($data->vehicleType !== null && $vehicle === null) {
                continue;
            }

            $results->push(new NearbyDriverData(
                id: $driverId,
                distance_meters: $this->kmToMeters($distanceKm),
                estimated_arrival_seconds: $this->calculateEta($distanceKm),
                vehicle: $vehicle !== null ? NearbyDriverVehicleData::fromModel($vehicle) : null,
                driver_rating: $this->resolveRating($driver->rating),
            ));
        }

        return $results;
    }

    /**
     * @param array<int, array{driver_id: string, distance_km: float}> $nearby
     *
     * @return array<string, float>
     */
    private function buildDistanceMap(array $nearby): array
    {
        $map = [];

        foreach ($nearby as $item) {
            $map[$item['driver_id']] = $item['distance_km'];
        }

        return $map;
    }

    /**
     * @param array<int, string> $driverIds
     *
     * @return Collection<string, User>
     */
    private function loadDriversWithRating(array $driverIds): Collection
    {
        return User::query()
            ->whereIn('id', $driverIds)
            ->with('vehicles')
            ->addSelect([
                'rating' => DB::table('ride_ratings')
                    ->selectRaw('AVG(rating)')
                    ->join('rides', 'rides.id', '=', 'ride_ratings.ride_id')
                    ->whereColumn('rides.driver_id', 'users.id'),
            ])
            ->get()
            ->keyBy('id');
    }

    /**
     * @param Collection<int, Vehicle> $vehicles
     */
    private function resolveVehicle(Collection $vehicles, ?\UnitEnum $filterByType): ?Vehicle
    {
        if ($filterByType !== null) {
            foreach ($vehicles as $vehicle) {
                if ($vehicle->vehicle_type === $filterByType) {
                    return $vehicle;
                }
            }

            return null;
        }

        return $vehicles->first();
    }

    private function kmToMeters(float $km): int
    {
        return (int) round($km * self::METERS_PER_KILOMETER);
    }

    private function calculateEta(float $distanceKm): int
    {
        return (int) round(($distanceKm / self::AVG_SPEED_KMH) * self::SECONDS_PER_HOUR);
    }

    private function resolveRating(mixed $rating): ?float
    {
        return is_numeric($rating) ? (float) $rating : null;
    }
}
