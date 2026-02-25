<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Referral Bonus Settings
    |--------------------------------------------------------------------------
    |
    | Credits awarded when a referral is completed (referee completes first ride).
    | Values are in MDL (local currency).
    |
    */
    'referral' => [
        'referrer_bonus' => 100, // Credits for the person who referred
        'referee_bonus'  => 50, // Credits for the new user
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Promo Code Settings
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'usage_limit_per_user' => 1,
        'min_order_amount'     => 0,
    ],
];
