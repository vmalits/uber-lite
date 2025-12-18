<?php

declare(strict_types=1);

use App\Actions\Auth\SelectUserRole;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

it('sets role when it is not set', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => null,
    ]);

    $action = app(SelectUserRole::class);

    $changed = $action->handle($user, UserRole::RIDER);

    expect($changed)->toBeTrue();
    $user->refresh();
    expect($user->role)->toBe(UserRole::RIDER)
        ->and($user->profile_step)->toBe(ProfileStep::PHONE_VERIFIED);
});

it('changes role when different', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => UserRole::RIDER,
    ]);

    $action = app(SelectUserRole::class);

    $changed = $action->handle($user, UserRole::DRIVER);

    expect($changed)->toBeTrue();
    $user->refresh();
    expect($user->role)->toBe(UserRole::DRIVER)
        ->and($user->profile_step)->toBe(ProfileStep::PHONE_VERIFIED);
});

it('is idempotent when the same role passed', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'phone_verified_at' => now(),
        'profile_step'      => ProfileStep::PHONE_VERIFIED,
        'role'              => UserRole::DRIVER,
    ]);

    $action = app(SelectUserRole::class);

    $changed = $action->handle($user, UserRole::DRIVER);

    expect($changed)->toBeFalse();
    $user->refresh();
    expect($user->role)->toBe(UserRole::DRIVER)
        ->and($user->profile_step)->toBe(ProfileStep::PHONE_VERIFIED);
});
