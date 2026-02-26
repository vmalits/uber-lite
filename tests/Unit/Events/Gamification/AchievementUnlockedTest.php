<?php

declare(strict_types=1);

use App\Events\Gamification\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Broadcasting\Channel;

it('broadcasts on correct channel', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create();

    $event = new AchievementUnlocked($user, $achievement, 'silver', 500);

    expect($event->broadcastOn())->toBeArray()
        ->and($event->broadcastOn()[0])->toBeInstanceOf(Channel::class)
        ->and($event->broadcastOn()[0]->name)->toBe("user:{$user->id}");
});

it('broadcasts with correct event name', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create();

    $event = new AchievementUnlocked($user, $achievement, 'bronze', 100);

    expect($event->broadcastAs())->toBe('achievement.unlocked');
});

it('broadcasts with correct payload', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create([
        'name'          => 'First Ride',
        'points_reward' => 50,
    ]);

    $event = new AchievementUnlocked($user, $achievement, 'silver', 500);

    $payload = $event->broadcastWith();

    expect($payload)->toBe([
        'achievement_id' => $achievement->id,
        'name'           => 'First Ride',
        'points'         => 50,
        'tier'           => 'silver',
        'total_xp'       => 500,
    ]);
});

it('implements ShouldBroadcast interface', function () {
    $interfaces = class_implements(AchievementUnlocked::class);

    expect($interfaces)->toHaveKey(Illuminate\Contracts\Broadcasting\ShouldBroadcast::class);
});

it('stores user achievement and tier data', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create();

    $event = new AchievementUnlocked($user, $achievement, 'gold', 2500);

    expect($event->user->id)->toBe($user->id)
        ->and($event->achievement->id)->toBe($achievement->id)
        ->and($event->tier)->toBe('gold')
        ->and($event->totalXp)->toBe(2500);
});
