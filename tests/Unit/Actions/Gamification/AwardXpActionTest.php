<?php

declare(strict_types=1);

use App\Actions\Gamification\AwardXpAction;
use App\Enums\UserTier;
use App\Events\Gamification\LevelUp;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Event::fake([LevelUp::class]);
    Notification::fake();
});

it('creates user level if not exists', function () {
    $user = User::factory()->create();
    $action = app(AwardXpAction::class);

    $userLevel = $action->execute($user, 100);

    expect($userLevel)->toBeInstanceOf(UserLevel::class)
        ->and($userLevel->user_id)->toBe($user->id)
        ->and($userLevel->xp)->toBe(100)
        ->and($userLevel->level)->toBe(2)
        ->and($userLevel->tier)->toBe(UserTier::BRONZE);
});

it('updates existing user level', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'xp'      => 100,
        'level'   => 2,
        'tier'    => UserTier::BRONZE,
    ]);

    $action = app(AwardXpAction::class);
    $userLevel = $action->execute($user, 50);

    expect($userLevel->xp)->toBe(150)
        ->and($userLevel->level)->toBe(2);
});

it('dispatches level up event when tier changes', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'xp'      => 400,
        'tier'    => UserTier::BRONZE,
    ]);

    $action = app(AwardXpAction::class);
    $action->execute($user, 200);

    Event::assertDispatched(LevelUp::class, function ($event) use ($user) {
        return $event->user->id === $user->id
            && $event->oldTier === UserTier::BRONZE
            && $event->newTier === UserTier::SILVER;
    });
});

it('does not dispatch level up event when tier stays same', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'xp'      => 100,
        'tier'    => UserTier::BRONZE,
    ]);

    $action = app(AwardXpAction::class);
    $action->execute($user, 50);

    Event::assertNotDispatched(LevelUp::class);
});

it('correctly calculates level from xp', function () {
    $user = User::factory()->create();
    $action = app(AwardXpAction::class);

    $userLevel = $action->execute($user, 250);

    expect($userLevel->level)->toBe(3);
});

it('transitions through multiple tiers correctly', function () {
    $user = User::factory()->create();
    $action = app(AwardXpAction::class);

    $userLevel = $action->execute($user, 600);
    expect($userLevel->tier)->toBe(UserTier::SILVER);

    $userLevel = $action->execute($user, 1500);
    expect($userLevel->tier)->toBe(UserTier::GOLD);

    $userLevel = $action->execute($user, 3000);
    expect($userLevel->tier)->toBe(UserTier::PLATINUM);
});
