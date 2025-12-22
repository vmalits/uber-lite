<?php

declare(strict_types=1);

use App\Actions\Auth\ResolveNextAction;
use App\Enums\NextAction;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

it('returns select_role when role is not selected', function (): void {
    /** @var User $user */
    $user = User::factory()->make([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => null,
        'email'             => null,
        'email_verified_at' => null,
    ]);

    $resolver = app(ResolveNextAction::class);

    expect($resolver->handle($user))->toBe(NextAction::SELECT_ROLE);
});

it('returns add_email when role selected but email is absent', function (): void {
    /** @var User $user */
    $user = User::factory()->make([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => UserRole::RIDER,
        'email'             => null,
        'email_verified_at' => null,
    ]);

    $resolver = app(ResolveNextAction::class);

    expect($resolver->handle($user))->toBe(NextAction::ADD_EMAIL);
});

it('returns verify_email when email set but not verified', function (): void {
    /** @var User $user */
    $user = User::factory()->make([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::EMAIL_ADDED,
        'role'              => UserRole::DRIVER,
        'email'             => 'user@example.com',
        'email_verified_at' => null,
    ]);

    $resolver = app(ResolveNextAction::class);

    expect($resolver->handle($user))->toBe(NextAction::VERIFY_EMAIL);
});

it('returns complete_profile when email verified but profile not completed', function (): void {
    /** @var User $user */
    $user = User::factory()->make([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::EMAIL_VERIFIED,
        'role'              => UserRole::DRIVER,
        'email'             => 'user@example.com',
        'email_verified_at' => now(),
    ]);

    $resolver = app(ResolveNextAction::class);

    expect($resolver->handle($user))->toBe(NextAction::COMPLETE_PROFILE);
});

it('returns done when everything completed', function (): void {
    /** @var User $user */
    $user = User::factory()->make([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::COMPLETED,
        'role'              => UserRole::RIDER,
        'email'             => 'user@example.com',
        'email_verified_at' => now(),
    ]);

    $resolver = app(ResolveNextAction::class);

    expect($resolver->handle($user))->toBe(NextAction::DONE);
});

it('returns done when profile_step is completed even if role is missing', function (): void {
    /** @var User $user */
    $user = User::factory()->make([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::COMPLETED,
        'role'              => null,
    ]);

    $resolver = app(ResolveNextAction::class);

    expect($resolver->handle($user))->toBe(NextAction::DONE);
});
