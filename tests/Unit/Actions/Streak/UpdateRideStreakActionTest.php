<?php

declare(strict_types=1);

use App\Actions\Streak\UpdateRideStreakAction;
use App\Enums\StreakLevel;
use App\Events\Streak\StreakBroken;
use App\Events\Streak\StreakLevelUp;
use App\Models\User;
use App\Models\UserRideStreak;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
});

it('creates streak for first ride', function () {
    $user = User::factory()->create();
    $action = app(UpdateRideStreakAction::class);

    $streak = $action->handle($user, now());

    expect($streak->current_streak)->toBe(1)
        ->and($streak->longest_streak)->toBe(1)
        ->and($streak->last_ride_date->toDateString())->toBe(now()->toDateString());
});

it('increments streak for consecutive day', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 1,
        'longest_streak' => 1,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $streak = $action->handle($user, now());

    expect($streak->current_streak)->toBe(2)
        ->and($streak->longest_streak)->toBe(2);
});

it('does not increment for same day ride', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 5,
        'longest_streak' => 5,
        'last_ride_date' => now(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $streak = $action->handle($user, now());

    expect($streak->current_streak)->toBe(5);
});

it('resets streak after missed day', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 7,
        'longest_streak' => 7,
        'last_ride_date' => now()->subDays(2),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $streak = $action->handle($user, now());

    expect($streak->current_streak)->toBe(1)
        ->and($streak->longest_streak)->toBe(7);

    Event::assertDispatched(StreakBroken::class);
});

it('updates longest streak when current exceeds', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 5,
        'longest_streak' => 5,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $streak = $action->handle($user, now());

    expect($streak->current_streak)->toBe(6)
        ->and($streak->longest_streak)->toBe(6);
});

it('dispatches level up event when reaching bronze', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 2,
        'longest_streak' => 2,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $action->handle($user, now());

    Event::assertDispatched(StreakLevelUp::class, function ($event) {
        return $event->newLevel === StreakLevel::BRONZE;
    });
});

it('dispatches level up event when reaching silver', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 6,
        'longest_streak' => 6,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $action->handle($user, now());

    Event::assertDispatched(StreakLevelUp::class, function ($event) {
        return $event->newLevel === StreakLevel::SILVER;
    });
});

it('dispatches level up event when reaching gold', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 13,
        'longest_streak' => 13,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $action->handle($user, now());

    Event::assertDispatched(StreakLevelUp::class, function ($event) {
        return $event->newLevel === StreakLevel::GOLD;
    });
});

it('dispatches level up event when reaching platinum', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 29,
        'longest_streak' => 29,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $action->handle($user, now());

    Event::assertDispatched(StreakLevelUp::class, function ($event) {
        return $event->newLevel === StreakLevel::PLATINUM;
    });
});

it('does not dispatch level up when staying in same level', function () {
    $user = User::factory()->create();
    UserRideStreak::factory()->create([
        'user_id'        => $user->id,
        'current_streak' => 4,
        'longest_streak' => 4,
        'last_ride_date' => now()->subDay(),
    ]);

    $action = app(UpdateRideStreakAction::class);
    $action->handle($user, now());

    Event::assertNotDispatched(StreakLevelUp::class);
});
