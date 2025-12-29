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
];
