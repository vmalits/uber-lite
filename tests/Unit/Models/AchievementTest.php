<?php

declare(strict_types=1);

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use App\Models\UserAchievement;

it('has correct fillable attributes', function () {
    $achievement = new Achievement;

    expect($achievement->getFillable())->toBe([
        'name',
        'key',
        'description',
        'icon',
        'category',
        'target_value',
        'points_reward',
        'metadata',
        'is_active',
    ]);
});

it('casts attributes correctly', function () {
    $achievement = Achievement::factory()->create([
        'category'      => AchievementCategory::DRIVER,
        'target_value'  => 10,
        'points_reward' => 50,
        'metadata'      => ['key' => 'value'],
        'is_active'     => true,
    ]);

    expect($achievement->category)->toBeInstanceOf(AchievementCategory::class)
        ->and($achievement->target_value)->toBeInt()
        ->and($achievement->points_reward)->toBeInt()
        ->and($achievement->metadata)->toBeArray()
        ->and($achievement->is_active)->toBeBool();
});

it('has many user achievements', function () {
    $achievement = Achievement::factory()->create();
    UserAchievement::factory()->count(3)->create(['achievement_id' => $achievement->id]);

    expect($achievement->userAchievements)->toHaveCount(3)
        ->and($achievement->userAchievements->first())->toBeInstanceOf(UserAchievement::class);
});

it('scopes to active achievements', function () {
    Achievement::factory()->create(['is_active' => true]);
    Achievement::factory()->create(['is_active' => false]);

    $activeAchievements = Achievement::active()->get();

    expect($activeAchievements)->toHaveCount(1)
        ->and($activeAchievements->first()->is_active)->toBeTrue();
});

it('scopes to specific category', function () {
    Achievement::factory()->driver()->create();
    Achievement::factory()->rider()->create();
    Achievement::factory()->common()->create();

    $driverAchievements = Achievement::forCategory(AchievementCategory::DRIVER)->get();

    expect($driverAchievements)->toHaveCount(1)
        ->and($driverAchievements->first()->category)->toBe(AchievementCategory::DRIVER);
});

it('scopes to driver achievements including common', function () {
    Achievement::factory()->driver()->count(2)->create();
    Achievement::factory()->rider()->count(2)->create();
    Achievement::factory()->common()->count(1)->create();

    $driverAchievements = Achievement::forDrivers()->get();

    expect($driverAchievements)->toHaveCount(3);
    foreach ($driverAchievements as $achievement) {
        expect($achievement->category)->toBeIn([AchievementCategory::DRIVER, AchievementCategory::COMMON]);
    }
});

it('scopes to rider achievements including common', function () {
    Achievement::factory()->driver()->count(2)->create();
    Achievement::factory()->rider()->count(2)->create();
    Achievement::factory()->common()->count(1)->create();

    $riderAchievements = Achievement::forRiders()->get();

    expect($riderAchievements)->toHaveCount(3);
    foreach ($riderAchievements as $achievement) {
        expect($achievement->category)->toBeIn([AchievementCategory::RIDER, AchievementCategory::COMMON]);
    }
});
