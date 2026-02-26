<?php

declare(strict_types=1);

use App\Enums\UserTier;
use App\Models\User;
use App\Models\UserLevel;

it('has correct fillable attributes', function () {
    $userLevel = new UserLevel;

    expect($userLevel->getFillable())->toBe([
        'user_id',
        'level',
        'xp',
        'tier',
    ]);
});

it('casts attributes correctly', function () {
    $userLevel = UserLevel::factory()->create([
        'level' => 5,
        'xp'    => 450,
        'tier'  => UserTier::BRONZE,
    ]);

    expect($userLevel->level)->toBeInt()
        ->and($userLevel->xp)->toBeInt()
        ->and($userLevel->tier)->toBeInstanceOf(UserTier::class);
});

it('belongs to a user', function () {
    $userLevel = UserLevel::factory()->create();

    expect($userLevel->user)->toBeInstanceOf(User::class)
        ->and($userLevel->user->id)->toBe($userLevel->user_id);
});

it('calculates xp to next level correctly', function () {
    $userLevel = UserLevel::factory()->create([
        'xp'   => 100,
        'tier' => UserTier::BRONZE,
    ]);

    expect($userLevel->xpToNextLevel())->toBe(400);
});

it('returns null for xp to next level at platinum', function () {
    $userLevel = UserLevel::factory()->create([
        'xp'   => 6000,
        'tier' => UserTier::PLATINUM,
    ]);

    expect($userLevel->xpToNextLevel())->toBeNull();
});

it('returns next tier correctly', function () {
    $bronzeLevel = UserLevel::factory()->create(['tier' => UserTier::BRONZE]);
    $platinumLevel = UserLevel::factory()->create(['tier' => UserTier::PLATINUM]);

    expect($bronzeLevel->nextTier())->toBe(UserTier::SILVER)
        ->and($platinumLevel->nextTier())->toBeNull();
});

it('recalculates tier correctly', function () {
    $userLevel = UserLevel::factory()->create([
        'xp'   => 100,
        'tier' => UserTier::BRONZE,
    ]);

    $userLevel->xp = 600;
    $userLevel->recalculateTier();

    expect($userLevel->tier)->toBe(UserTier::SILVER);
});

it('adds xp and updates level correctly', function () {
    $userLevel = UserLevel::factory()->create([
        'xp'    => 0,
        'level' => 1,
        'tier'  => UserTier::BRONZE,
    ]);

    $leveledUp = $userLevel->addXp(150);

    expect($userLevel->xp)->toBe(150)
        ->and($userLevel->level)->toBe(2)
        ->and($leveledUp)->toBeFalse();
});

it('detects tier change when adding xp', function () {
    $userLevel = UserLevel::factory()->create([
        'xp'    => 0,
        'level' => 1,
        'tier'  => UserTier::BRONZE,
    ]);

    $leveledUp = $userLevel->addXp(600);

    expect($userLevel->xp)->toBe(600)
        ->and($userLevel->tier)->toBe(UserTier::SILVER)
        ->and($leveledUp)->toBeTrue();
});

it('does not trigger tier change when staying in same tier', function () {
    $userLevel = UserLevel::factory()->create([
        'xp'    => 100,
        'level' => 2,
        'tier'  => UserTier::BRONZE,
    ]);

    $leveledUp = $userLevel->addXp(100);

    expect($userLevel->xp)->toBe(200)
        ->and($userLevel->tier)->toBe(UserTier::BRONZE)
        ->and($leveledUp)->toBeFalse();
});
