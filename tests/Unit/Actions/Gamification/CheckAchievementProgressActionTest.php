<?php

declare(strict_types=1);

use App\Actions\Gamification\CheckAchievementProgressAction;
use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
});

it('returns null for inactive achievement', function () {
    $user = User::factory()->create();
    Achievement::factory()->create([
        'key'       => 'test_achievement',
        'is_active' => false,
    ]);

    $action = app(CheckAchievementProgressAction::class);
    $result = $action->handle($user, 'test_achievement');

    expect($result)->toBeNull();
});

it('returns null for non-existent achievement', function () {
    $user = User::factory()->create();

    $action = app(CheckAchievementProgressAction::class);
    $result = $action->handle($user, 'non_existent');

    expect($result)->toBeNull();
});

it('creates user achievement with zero progress', function () {
    $user = User::factory()->create();
    Achievement::factory()->create([
        'key'          => 'test_achievement',
        'target_value' => 10,
        'is_active'    => true,
    ]);

    $action = app(CheckAchievementProgressAction::class);
    $result = $action->handle($user, 'test_achievement', 0);

    expect($result)->toBeInstanceOf(UserAchievement::class)
        ->and($result->progress)->toBe(0)
        ->and($result->completed_at)->toBeNull();
});

it('increments progress', function () {
    $user = User::factory()->create();
    Achievement::factory()->create([
        'key'          => 'test_achievement',
        'target_value' => 10,
        'is_active'    => true,
    ]);

    $action = app(CheckAchievementProgressAction::class);
    $action->handle($user, 'test_achievement', 3);
    $result = $action->handle($user, 'test_achievement', 2);

    expect($result->progress)->toBe(5);
});

it('completes achievement when target reached', function () {
    $user = User::factory()->create();
    Achievement::factory()->create([
        'key'           => 'test_achievement',
        'target_value'  => 5,
        'points_reward' => 50,
        'is_active'     => true,
    ]);

    $action = app(CheckAchievementProgressAction::class);
    $result = $action->handle($user, 'test_achievement', 5);

    expect($result->completed_at)->not->toBeNull()
        ->and($result->isCompleted())->toBeTrue();
});

it('does not increment completed achievement', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create([
        'key'          => 'test_achievement',
        'target_value' => 5,
        'is_active'    => true,
    ]);

    UserAchievement::factory()->create([
        'user_id'        => $user->id,
        'achievement_id' => $achievement->id,
        'progress'       => 5,
        'completed_at'   => now(),
    ]);

    $action = app(CheckAchievementProgressAction::class);
    $result = $action->handle($user, 'test_achievement', 10);

    expect($result->progress)->toBe(5);
});

it('handles increment by parameter', function () {
    $user = User::factory()->create();
    Achievement::factory()->create([
        'key'          => 'test_achievement',
        'target_value' => 100,
        'is_active'    => true,
    ]);

    $action = app(CheckAchievementProgressAction::class);
    $result = $action->handle($user, 'test_achievement', 25);

    expect($result->progress)->toBe(25);
});
