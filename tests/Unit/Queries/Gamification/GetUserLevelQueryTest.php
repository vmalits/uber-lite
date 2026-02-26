<?php

declare(strict_types=1);

use App\Enums\UserTier;
use App\Models\User;
use App\Models\UserLevel;
use App\Queries\Gamification\GetUserLevelQuery;

it('returns default level data for user without level record', function () {
    $user = User::factory()->create();
    $query = app(GetUserLevelQuery::class);

    $result = $query->execute($user);

    expect($result->level)->toBe(1)
        ->and($result->xp)->toBe(0)
        ->and($result->tier)->toBe('bronze')
        ->and($result->xpToNextTier)->toBe(500)
        ->and($result->nextTier)->toBe('silver')
        ->and($result->tierThreshold)->toBe(0);
});

it('returns correct level data for bronze user', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'level'   => 3,
        'xp'      => 250,
        'tier'    => UserTier::BRONZE,
    ]);

    $query = app(GetUserLevelQuery::class);
    $result = $query->execute($user);

    expect($result->level)->toBe(3)
        ->and($result->xp)->toBe(250)
        ->and($result->tier)->toBe('bronze')
        ->and($result->xpToNextTier)->toBe(250)
        ->and($result->nextTier)->toBe('silver')
        ->and($result->tierThreshold)->toBe(0);
});

it('returns correct level data for silver user', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'level'   => 10,
        'xp'      => 1000,
        'tier'    => UserTier::SILVER,
    ]);

    $query = app(GetUserLevelQuery::class);
    $result = $query->execute($user);

    expect($result->level)->toBe(10)
        ->and($result->xp)->toBe(1000)
        ->and($result->tier)->toBe('silver')
        ->and($result->xpToNextTier)->toBe(1000)
        ->and($result->nextTier)->toBe('gold')
        ->and($result->tierThreshold)->toBe(500);
});

it('returns correct level data for gold user', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'level'   => 30,
        'xp'      => 3000,
        'tier'    => UserTier::GOLD,
    ]);

    $query = app(GetUserLevelQuery::class);
    $result = $query->execute($user);

    expect($result->level)->toBe(30)
        ->and($result->xp)->toBe(3000)
        ->and($result->tier)->toBe('gold')
        ->and($result->xpToNextTier)->toBe(2000)
        ->and($result->nextTier)->toBe('platinum')
        ->and($result->tierThreshold)->toBe(2000);
});

it('returns correct level data for platinum user', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'level'   => 60,
        'xp'      => 7000,
        'tier'    => UserTier::PLATINUM,
    ]);

    $query = app(GetUserLevelQuery::class);
    $result = $query->execute($user);

    expect($result->level)->toBe(60)
        ->and($result->xp)->toBe(7000)
        ->and($result->tier)->toBe('platinum')
        ->and($result->xpToNextTier)->toBeNull()
        ->and($result->nextTier)->toBeNull()
        ->and($result->tierThreshold)->toBe(5000);
});
