<?php

declare(strict_types=1);

use App\Enums\UserTier;

it('has all expected cases', function () {
    $expected = ['BRONZE', 'SILVER', 'GOLD', 'PLATINUM'];
    $actual = array_map(fn ($c) => $c->name, UserTier::cases());
    expect($actual)->toEqualCanonicalizing($expected);
});

it('returns correct threshold for each tier', function () {
    expect(UserTier::BRONZE->threshold())->toBe(0)
        ->and(UserTier::SILVER->threshold())->toBe(500)
        ->and(UserTier::GOLD->threshold())->toBe(2000)
        ->and(UserTier::PLATINUM->threshold())->toBe(5000);
});

it('returns correct next tier', function () {
    expect(UserTier::BRONZE->nextTier())->toBe(UserTier::SILVER)
        ->and(UserTier::SILVER->nextTier())->toBe(UserTier::GOLD)
        ->and(UserTier::GOLD->nextTier())->toBe(UserTier::PLATINUM)
        ->and(UserTier::PLATINUM->nextTier())->toBeNull();
});

it('determines tier from xp correctly', function () {
    expect(UserTier::fromXp(0))->toBe(UserTier::BRONZE)
        ->and(UserTier::fromXp(100))->toBe(UserTier::BRONZE)
        ->and(UserTier::fromXp(499))->toBe(UserTier::BRONZE)
        ->and(UserTier::fromXp(500))->toBe(UserTier::SILVER)
        ->and(UserTier::fromXp(1000))->toBe(UserTier::SILVER)
        ->and(UserTier::fromXp(1999))->toBe(UserTier::SILVER)
        ->and(UserTier::fromXp(2000))->toBe(UserTier::GOLD)
        ->and(UserTier::fromXp(3000))->toBe(UserTier::GOLD)
        ->and(UserTier::fromXp(4999))->toBe(UserTier::GOLD)
        ->and(UserTier::fromXp(5000))->toBe(UserTier::PLATINUM)
        ->and(UserTier::fromXp(10000))->toBe(UserTier::PLATINUM);
});

it('calculates xp to next level correctly', function () {
    expect(UserTier::BRONZE->xpToNextLevel(0))->toBe(500)
        ->and(UserTier::BRONZE->xpToNextLevel(250))->toBe(250)
        ->and(UserTier::SILVER->xpToNextLevel(500))->toBe(1500)
        ->and(UserTier::SILVER->xpToNextLevel(1000))->toBe(1000)
        ->and(UserTier::GOLD->xpToNextLevel(2000))->toBe(3000)
        ->and(UserTier::PLATINUM->xpToNextLevel(5000))->toBeNull();
});
