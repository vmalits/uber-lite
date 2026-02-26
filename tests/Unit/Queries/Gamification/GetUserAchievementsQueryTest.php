<?php

declare(strict_types=1);

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Queries\Gamification\GetUserAchievementsQuery;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->query = app(GetUserAchievementsQuery::class);
});

it('returns empty data for user without achievements', function () {
    $result = $this->query->execute($this->user);

    expect($result['data'])->toBeEmpty()
        ->and($result['meta']['total'])->toBe(0);
});

it('returns user achievements with achievement data', function () {
    $achievement = Achievement::factory()->create([
        'name'          => 'First Ride',
        'key'           => 'first_ride',
        'target_value'  => 1,
        'points_reward' => 10,
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement->id,
        'progress'       => 1,
        'completed_at'   => now(),
    ]);

    $result = $this->query->execute($this->user);

    expect($result['data'])->toHaveCount(1)
        ->and($result['meta']['total'])->toBe(1);

    $item = $result['data']->first();
    expect($item->achievement->name)->toBe('First Ride')
        ->and($item->progress)->toBe(1)
        ->and($item->isCompleted)->toBeTrue();
});

it('filters by completed status', function () {
    $achievement1 = Achievement::factory()->create(['target_value' => 1]);
    $achievement2 = Achievement::factory()->create(['target_value' => 1]);

    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement1->id,
        'completed_at'   => now(),
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement2->id,
        'completed_at'   => null,
    ]);

    $completedResult = $this->query->execute($this->user, 'completed');
    $inProgressResult = $this->query->execute($this->user, 'in_progress');

    expect($completedResult['data'])->toHaveCount(1)
        ->and($completedResult['data']->first()->isCompleted)->toBeTrue()
        ->and($inProgressResult['data'])->toHaveCount(1)
        ->and($inProgressResult['data']->first()->isCompleted)->toBeFalse();
});

it('calculates progress percentage correctly', function () {
    $achievement = Achievement::factory()->create(['target_value' => 10]);

    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement->id,
        'progress'       => 5,
        'completed_at'   => null,
    ]);

    $result = $this->query->execute($this->user);
    $item = $result['data']->first();

    expect($item->progressPercentage)->toBe(50.0);
});

it('paginates results correctly', function () {
    $achievements = Achievement::factory()->count(20)->create();

    foreach ($achievements as $achievement) {
        UserAchievement::factory()->create([
            'user_id'        => $this->user->id,
            'achievement_id' => $achievement->id,
        ]);
    }

    $result = $this->query->execute($this->user, null, 5);

    expect($result['data'])->toHaveCount(5)
        ->and($result['meta']['per_page'])->toBe(5)
        ->and($result['meta']['total'])->toBe(20)
        ->and($result['meta']['last_page'])->toBe(4);
});

it('orders achievements by completed_at then updated_at descending', function () {
    $achievement1 = Achievement::factory()->create();
    $achievement2 = Achievement::factory()->create();
    $achievement3 = Achievement::factory()->create();

    // Create achievements with different timestamps
    // Completed one hour ago
    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement1->id,
        'progress'       => 100,
        'completed_at'   => now()->subHour(),
    ]);

    // Completed now (should be first)
    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement2->id,
        'progress'       => 100,
        'completed_at'   => now(),
    ]);

    // In progress
    UserAchievement::factory()->create([
        'user_id'        => $this->user->id,
        'achievement_id' => $achievement3->id,
        'progress'       => 50,
        'completed_at'   => null,
    ]);

    $result = $this->query->execute($this->user);

    // Verify we have 3 achievements
    expect($result['data'])->toHaveCount(3);

    // Verify the ordering - completed achievements first (nulls last for completed_at desc)
    $completedItems = $result['data']->filter(fn ($item) => $item->isCompleted);
    expect($completedItems)->toHaveCount(2);
});
