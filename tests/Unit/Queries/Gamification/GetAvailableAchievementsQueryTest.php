<?php

declare(strict_types=1);

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use App\Queries\Gamification\GetAvailableAchievementsQuery;

beforeEach(function () {
    $this->query = app(GetAvailableAchievementsQuery::class);
});

it('returns only active achievements', function () {
    Achievement::factory()->create(['is_active' => true, 'category' => AchievementCategory::DRIVER]);
    Achievement::factory()->create(['is_active' => false, 'category' => AchievementCategory::DRIVER]);

    $result = $this->query->execute(AchievementCategory::DRIVER);

    expect($result)->toHaveCount(1);
});

it('returns driver achievements including common', function () {
    Achievement::factory()->driver()->count(2)->create(['is_active' => true]);
    Achievement::factory()->rider()->count(2)->create(['is_active' => true]);
    Achievement::factory()->common()->count(1)->create(['is_active' => true]);

    $result = $this->query->execute(AchievementCategory::DRIVER);

    expect($result)->toHaveCount(3);
    foreach ($result as $achievement) {
        expect($achievement->category)->toBeIn(['driver', 'common']);
    }
});

it('returns rider achievements including common', function () {
    Achievement::factory()->driver()->count(2)->create(['is_active' => true]);
    Achievement::factory()->rider()->count(2)->create(['is_active' => true]);
    Achievement::factory()->common()->count(1)->create(['is_active' => true]);

    $result = $this->query->execute(AchievementCategory::RIDER);

    expect($result)->toHaveCount(3);
    foreach ($result as $achievement) {
        expect($achievement->category)->toBeIn(['rider', 'common']);
    }
});

it('returns achievement data with correct structure', function () {
    Achievement::factory()->create([
        'name'          => 'First Ride',
        'key'           => 'driver_first_ride',
        'description'   => 'Complete your first ride',
        'icon'          => 'trophy',
        'category'      => AchievementCategory::DRIVER,
        'target_value'  => 1,
        'points_reward' => 10,
        'is_active'     => true,
    ]);

    $result = $this->query->execute(AchievementCategory::DRIVER);
    $achievement = $result->first();

    expect($achievement->name)->toBe('First Ride')
        ->and($achievement->key)->toBe('driver_first_ride')
        ->and($achievement->description)->toBe('Complete your first ride')
        ->and($achievement->icon)->toBe('trophy')
        ->and($achievement->category)->toBe('driver')
        ->and($achievement->target_value)->toBe(1)
        ->and($achievement->points_reward)->toBe(10);
});

it('returns empty collection when no achievements exist', function () {
    $result = $this->query->execute(AchievementCategory::DRIVER);

    expect($result)->toBeEmpty();
});

it('excludes inactive achievements', function () {
    Achievement::factory()->driver()->create(['is_active' => true]);
    Achievement::factory()->driver()->create(['is_active' => false]);
    Achievement::factory()->common()->create(['is_active' => false]);

    $result = $this->query->execute(AchievementCategory::DRIVER);

    expect($result)->toHaveCount(1);
});
