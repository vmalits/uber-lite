<?php

declare(strict_types=1);

return [
    'auth' => [
        'phone_required'      => 'Phone number is required',
        'phone_invalid'       => 'Invalid phone number',
        'otp_sent'            => 'OTP code sent successfully',
        'otp_expired'         => 'OTP code has expired',
        'otp_invalid'         => 'Invalid OTP code',
        'verification_failed' => 'Verification failed',
        'unauthorized'        => 'Unauthorized',
        'user_not_found'      => 'User not found',
    ],
    'validation' => [
        'required' => 'The :attribute field is required',
        'email'    => 'The :attribute must be a valid email address',
        'min'      => 'The :attribute must be at least :min characters',
        'max'      => 'The :attribute may not be greater than :max characters',
    ],
    'errors' => [
        'server_error'      => 'Server error',
        'not_found'         => 'Resource not found',
        'forbidden'         => 'Forbidden.',
        'invalid_locale'    => 'Invalid locale',
        'unauthorized'      => 'Unauthenticated.',
        'validation_failed' => 'The given data was invalid.',
        'too_many_requests' => 'Too many requests.',
    ],
    'success' => [
        'created'        => 'Created successfully',
        'updated'        => 'Updated successfully',
        'deleted'        => 'Deleted successfully',
        'locale_updated' => 'Locale updated successfully',
    ],
];
