<?php

declare(strict_types=1);

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;

it('has correct fillable attributes', function () {
    $userAchievement = new UserAchievement;

    expect($userAchievement->getFillable())->toBe([
        'user_id',
        'achievement_id',
        'progress',
        'completed_at',
    ]);
});

it('casts attributes correctly', function () {
    $userAchievement = UserAchievement::factory()->create([
        'progress'     => 5,
        'completed_at' => now(),
    ]);

    expect($userAchievement->progress)->toBeInt()
        ->and($userAchievement->completed_at)->toBeInstanceOf(Carbon\CarbonImmutable::class);
});

it('belongs to a user', function () {
    $userAchievement = UserAchievement::factory()->create();

    expect($userAchievement->user)->toBeInstanceOf(User::class)
        ->and($userAchievement->user->id)->toBe($userAchievement->user_id);
});

it('belongs to an achievement', function () {
    $userAchievement = UserAchievement::factory()->create();

    expect($userAchievement->achievement)->toBeInstanceOf(Achievement::class)
        ->and($userAchievement->achievement->id)->toBe($userAchievement->achievement_id);
});

it('determines if completed correctly', function () {
    $completedAchievement = UserAchievement::factory()->create(['completed_at' => now()]);
    $inProgressAchievement = UserAchievement::factory()->create(['completed_at' => null]);

    expect($completedAchievement->isCompleted())->toBeTrue()
        ->and($inProgressAchievement->isCompleted())->toBeFalse();
});

it('calculates progress percentage correctly', function () {
    $achievement = Achievement::factory()->create(['target_value' => 10]);
    $userAchievement = UserAchievement::factory()->create([
        'achievement_id' => $achievement->id,
        'progress'       => 5,
    ]);

    expect($userAchievement->progressPercentage())->toBe(50.0);
});

it('caps progress percentage at 100', function () {
    $achievement = Achievement::factory()->create(['target_value' => 10]);
    $userAchievement = UserAchievement::factory()->create([
        'achievement_id' => $achievement->id,
        'progress'       => 15,
    ]);

    expect($userAchievement->progressPercentage())->toBe(100.0);
});

it('returns zero percentage for zero target value', function () {
    $achievement = Achievement::factory()->create(['target_value' => 0]);
    $userAchievement = UserAchievement::factory()->create([
        'achievement_id' => $achievement->id,
        'progress'       => 5,
    ]);

    expect($userAchievement->progressPercentage())->toBe(0.0);
});

it('scopes to completed achievements', function () {
    UserAchievement::factory()->create(['completed_at' => now()]);
    UserAchievement::factory()->create(['completed_at' => null]);

    $completed = UserAchievement::completed()->get();

    expect($completed)->toHaveCount(1)
        ->and($completed->first()->completed_at)->not->toBeNull();
});

it('scopes to in progress achievements', function () {
    UserAchievement::factory()->create(['completed_at' => now()]);
    UserAchievement::factory()->create(['completed_at' => null]);

    $inProgress = UserAchievement::inProgress()->get();

    expect($inProgress)->toHaveCount(1)
        ->and($inProgress->first()->completed_at)->toBeNull();
});
