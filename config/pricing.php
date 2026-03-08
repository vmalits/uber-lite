<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Pricing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for ride pricing in Moldova (MDL currency)
    |
    */

    'estimation' => [
        'base_fare'    => 15.0,
        'per_km'       => 8.0,
        'per_minute'   => 2.0,
        'minimum_fare' => 20.0,
    ],

    'fixed_rates' => [
        'base_fee'   => 25.0,
        'per_km'     => 6.5,
        'per_minute' => 1.2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pricing Zones
    |--------------------------------------------------------------------------
    |
    | Geographic zones with surge pricing multipliers.
    | Coordinates are in [lat, lng] format.
    |
    */

    'zones' => [
        [
            'id'               => 'centru-chisinau',
            'name'             => 'Centru, Chișinău',
            'enabled'          => true,
            'surge_multiplier' => 1.2,
            'reason'           => 'high_demand_area',
            'center'           => ['lat' => 47.0268, 'lng' => 28.8416],
            'radius_meters'    => 1500,
        ],
        [
            'id'               => 'botanica-chisinau',
            'name'             => 'Botanica, Chișinău',
            'enabled'          => true,
            'surge_multiplier' => 1.0,
            'reason'           => null,
            'center'           => ['lat' => 46.9986, 'lng' => 28.8574],
            'radius_meters'    => 2000,
        ],
        [
            'id'               => 'buiucani-chisinau',
            'name'             => 'Buiucani, Chișinău',
            'enabled'          => true,
            'surge_multiplier' => 1.15,
            'reason'           => 'event_zone',
            'center'           => ['lat' => 47.0367, 'lng' => 28.8156],
            'radius_meters'    => 1800,
        ],
        [
            'id'               => 'aeroport-chisinau',
            'name'             => 'Aeroport Chișinău',
            'enabled'          => true,
            'surge_multiplier' => 1.5,
            'reason'           => 'airport_zone',
            'center'           => ['lat' => 46.9277, 'lng' => 28.9313],
            'radius_meters'    => 3000,
        ],
    ],
];
