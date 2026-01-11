<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | OTP Code Length
    |--------------------------------------------------------------------------
    |
    | This option defines the length of the OTP code generated.
    |
    */

    'code_length' => env('OTP_CODE_LENGTH', 6),

    /*
    |--------------------------------------------------------------------------
    | OTP Expiration Time
    |--------------------------------------------------------------------------
    |
    | This option defines the number of minutes an OTP code is valid.
    |
    */

    'expiration_minutes' => env('OTP_EXPIRATION_MINUTES', 5),

    /*
    |--------------------------------------------------------------------------
    | OTP Request Rate Limiting
    |--------------------------------------------------------------------------
    |
    | These options define the rate limiting rules for OTP request/resend endpoints.
    |
    */

    'rate_limit' => [
        'max_attempts'  => env('OTP_RATE_LIMIT_MAX_ATTEMPTS', 3),
        'decay_minutes' => env('OTP_RATE_LIMIT_DECAY_MINUTES', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Verification Rate Limiting
    |--------------------------------------------------------------------------
    |
    | These options define the rate limiting rules for OTP verify endpoint (IP + phone combo).
    |
    */

    'verify' => [
        'rate_limit' => [
            'max_attempts'  => env('OTP_VERIFY_RATE_LIMIT_MAX_ATTEMPTS', 5),
            'decay_minutes' => env('OTP_VERIFY_RATE_LIMIT_DECAY_MINUTES', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Verification Failed Attempts
    |--------------------------------------------------------------------------
    |
    | These options define the blocking rules for failed OTP verification attempts.
    |
    */

    'failed_attempts' => [
        'threshold'      => env('OTP_FAILED_ATTEMPTS_THRESHOLD', 3),
        'decay_minutes'  => env('OTP_FAILED_ATTEMPTS_DECAY_MINUTES', 10),
        'block_duration' => env('OTP_BLOCK_DURATION_MINUTES', 15),
    ],

];
