<?php

declare(strict_types=1);

return [
    'redis' => [
        'geo_key'          => env('DRIVER_LOCATION_GEO_KEY', 'drivers:geo'),
        'online_key'       => env('DRIVER_LOCATION_ONLINE_KEY', 'drivers:online'),
        'ttl'              => env('DRIVER_LOCATION_TTL', 30),
        'search_radius_km' => env('DRIVER_LOCATION_SEARCH_RADIUS_KM', 5),
        'search_count'     => env('DRIVER_LOCATION_SEARCH_COUNT', 20),
    ],
    'publish' => [
        'enabled'              => env('DRIVER_LOCATION_PUBLISH_ENABLED', true),
        'min_interval_seconds' => env('DRIVER_LOCATION_PUBLISH_MIN_INTERVAL', 2),
        'min_distance_meters'  => env('DRIVER_LOCATION_PUBLISH_MIN_DISTANCE', 10),
        'admin_region'         => env('DRIVER_LOCATION_PUBLISH_ADMIN_REGION', true),
        'region_precision'     => env('DRIVER_LOCATION_REGION_PRECISION', 2),
    ],
];
