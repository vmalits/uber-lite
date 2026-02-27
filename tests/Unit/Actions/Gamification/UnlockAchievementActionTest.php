<?php

declare(strict_types=1);

use App\Actions\Gamification\UnlockAchievementAction;
use App\Events\Gamification\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Event::fake([AchievementUnlocked::class]);
    Notification::fake();
});

it('awards xp when achievement is unlocked', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create([
        'points_reward' => 50,
    ]);

    $action = app(UnlockAchievementAction::class);
    $action->handle($user, $achievement);

    $userLevel = UserLevel::where('user_id', $user->id)->first();

    expect($userLevel)->not->toBeNull()
        ->and($userLevel->xp)->toBe(50);
});

it('dispatches achievement unlocked event', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create([
        'points_reward' => 100,
    ]);

    $action = app(UnlockAchievementAction::class);
    $action->handle($user, $achievement);

    Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($user, $achievement) {
        return $event->user->id === $user->id
            && $event->achievement->id === $achievement->id
            && $event->totalXp === 100;
    });
});

it('includes tier and xp in dispatched event', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'xp'      => 500,
    ]);

    $achievement = Achievement::factory()->create([
        'points_reward' => 100,
    ]);

    $action = app(UnlockAchievementAction::class);
    $action->handle($user, $achievement);

    Event::assertDispatched(AchievementUnlocked::class, function ($event) {
        return $event->tier === 'silver'
            && $event->totalXp === 600;
    });
});

it('accumulates xp from multiple achievements', function () {
    $user = User::factory()->create();
    $achievement1 = Achievement::factory()->create(['points_reward' => 30]);
    $achievement2 = Achievement::factory()->create(['points_reward' => 20]);

    $action = app(UnlockAchievementAction::class);
    $action->handle($user, $achievement1);
    $action->handle($user, $achievement2);

    $userLevel = UserLevel::where('user_id', $user->id)->first();

    expect($userLevel->xp)->toBe(50);
});
