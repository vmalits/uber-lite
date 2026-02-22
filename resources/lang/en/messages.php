<?php

declare(strict_types=1);

return [
    'auth' => [
        'phone_required'           => 'Phone number is required',
        'phone_invalid'            => 'Invalid phone number',
        'otp_sent'                 => 'OTP code sent successfully',
        'otp_expired'              => 'OTP code has expired',
        'otp_invalid'              => 'Invalid OTP code',
        'verification_failed'      => 'Verification failed',
        'unauthorized'             => 'Unauthorized',
        'user_not_found'           => 'User not found',
        'logged_out'               => 'Logged out successfully.',
        'account_deleted'          => 'Account deleted successfully.',
        'email_added'              => 'Email added successfully.',
        'phone_not_verified'       => 'Phone is not verified.',
        'role_selected'            => 'Role selected successfully.',
        'profile_completed'        => 'Profile completed successfully.',
        'otp_requested'            => 'OTP has been requested successfully.',
        'otp_resent'               => 'OTP has been resent successfully.',
        'email_verify_link'        => 'Invalid verification link.',
        'email_already_verified'   => 'Your email is already verified.',
        'email_verified'           => 'Email verified successfully.',
        'verification_link_sent'   => 'Verification link has been sent.',
        'too_many_attempts'        => 'Too many failed verification attempts. Please request a new OTP.',
        'phone_already_verified'   => 'Phone is already verified.',
        'verification_successful'  => 'Verification successful.',
        'invalid_or_expired_code'  => 'Invalid or expired code.',
        'phone_not_verified_error' => 'Phone is not verified.',
        'role_already_selected'    => 'Role is already selected.',
        'email_not_set'            => 'Email is not set.',
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
    'ride' => [
        'created'             => 'Ride created successfully.',
        'scheduled'           => 'Ride scheduled successfully.',
        'accepted'            => 'Ride accepted successfully.',
        'completed'           => 'Ride completed successfully.',
        'cancelled'           => 'Ride cancelled successfully.',
        'rated'               => 'Ride rated successfully.',
        'not_active'          => 'No active ride found.',
        'already_active'      => 'You already have an active ride.',
        'estimate_calculated' => 'Estimate calculated successfully.',
    ],
    'profile' => [
        'updated' => 'Profile updated successfully.',
    ],
    'avatar' => [
        'upload_started' => 'Avatar upload processing started.',
    ],
    'driver' => [
        'online'  => 'You are now online and available for rides.',
        'offline' => 'You are now offline and will not receive ride requests.',
    ],
    'favorite' => [
        'location_added'   => 'Favorite location added successfully.',
        'location_removed' => 'Favorite location removed successfully.',
    ],
    'vehicle' => [
        'added' => 'Vehicle added successfully.',
    ],
    'support' => [
        'ticket_created' => 'Support ticket created successfully.',
    ],
    'payment_methods' => [
        'fetched' => 'Payment methods fetched successfully.',
    ],
];
