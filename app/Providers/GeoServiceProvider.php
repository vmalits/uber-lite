<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\GeocodingDriver;
use App\Services\Geo\CachedGeocodingService;
use App\Services\Geo\FakeGeocodingService;
use App\Services\Geo\GeocodingServiceInterface;
use App\Services\Geo\NominatimGeocodingService;
use App\Services\Geo\OpenStreetMapGeocodingService;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

final class GeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(FakeGeocodingService::class);
        $this->app->scoped(OpenStreetMapGeocodingService::class);

        $this->app->scoped(NominatimGeocodingService::class, function (): NominatimGeocodingService {
            return NominatimGeocodingService::fromConfig(
                http: $this->app->make(Factory::class),
                logger: $this->app->make(LoggerInterface::class),
            );
        });

        $this->app->scoped(GeocodingServiceInterface::class, function (): GeocodingServiceInterface {
            $driverValue = config('services.geocoding.driver', 'osm');
            $driver = \is_string($driverValue) ? GeocodingDriver::fromValue($driverValue) : null;
            $driver = $driver ?? GeocodingDriver::OSM;

            $innerService = match ($driver) {
                GeocodingDriver::Fake => $this->app->make(FakeGeocodingService::class),
                GeocodingDriver::OSM, GeocodingDriver::OpenStreetMap => $this->app->make(
                    OpenStreetMapGeocodingService::class,
                ),
                GeocodingDriver::Nominatim => $this->app->make(NominatimGeocodingService::class),
            };

            $cacheTtl = config('services.geocoding.cache_ttl', 86400);

            return new CachedGeocodingService(
                decorated: $innerService,
                cache: $this->app->make(Repository::class),
                cacheTtl: \is_int($cacheTtl) ? $cacheTtl : 86400,
                logger: $this->app->make(LoggerInterface::class),
            );
        });
    }

    public function boot(): void {}
}
