<?php

declare(strict_types=1);

use App\Enums\AchievementCategory;

it('has all expected cases', function () {
    $expected = ['DRIVER', 'RIDER', 'COMMON'];
    $actual = array_map(fn ($c) => $c->name, AchievementCategory::cases());
    expect($actual)->toEqualCanonicalizing($expected);
});

it('has correct string values', function () {
    expect(AchievementCategory::DRIVER->value)->toBe('driver')
        ->and(AchievementCategory::RIDER->value)->toBe('rider')
        ->and(AchievementCategory::COMMON->value)->toBe('common');
});
