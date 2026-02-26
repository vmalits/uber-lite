<?php

declare(strict_types=1);

use App\Enums\UserTier;
use App\Events\Gamification\LevelUp;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Broadcasting\Channel;

it('broadcasts on correct channel', function () {
    $user = User::factory()->create();

    $event = new LevelUp($user, UserTier::BRONZE, UserTier::SILVER);

    expect($event->broadcastOn())->toBeArray()
        ->and($event->broadcastOn()[0])->toBeInstanceOf(Channel::class)
        ->and($event->broadcastOn()[0]->name)->toBe("user:{$user->id}");
});

it('broadcasts with correct event name', function () {
    $user = User::factory()->create();

    $event = new LevelUp($user, UserTier::BRONZE, UserTier::SILVER);

    expect($event->broadcastAs())->toBe('level.up');
});

it('broadcasts with correct payload including level data', function () {
    $user = User::factory()->create();
    UserLevel::factory()->create([
        'user_id' => $user->id,
        'level'   => 10,
        'xp'      => 1000,
    ]);

    $event = new LevelUp($user, UserTier::BRONZE, UserTier::SILVER);
    $payload = $event->broadcastWith();

    expect($payload)->toBe([
        'old_tier' => 'bronze',
        'new_tier' => 'silver',
        'level'    => 10,
        'xp'       => 1000,
    ]);
});

it('handles missing user level gracefully', function () {
    $user = User::factory()->create();

    $event = new LevelUp($user, UserTier::SILVER, UserTier::GOLD);
    $payload = $event->broadcastWith();

    expect($payload['level'])->toBe(1)
        ->and($payload['xp'])->toBe(0);
});

it('implements ShouldBroadcast interface', function () {
    $interfaces = class_implements(LevelUp::class);

    expect($interfaces)->toHaveKey(Illuminate\Contracts\Broadcasting\ShouldBroadcast::class);
});

it('stores user and tier data correctly', function () {
    $user = User::factory()->create();

    $event = new LevelUp($user, UserTier::GOLD, UserTier::PLATINUM);

    expect($event->user->id)->toBe($user->id)
        ->and($event->oldTier)->toBe(UserTier::GOLD)
        ->and($event->newTier)->toBe(UserTier::PLATINUM);
});

it('tracks tier transitions correctly', function () {
    $user = User::factory()->create();

    // Bronze to Silver
    $event1 = new LevelUp($user, UserTier::BRONZE, UserTier::SILVER);
    expect($event1->oldTier->value)->toBe('bronze')
        ->and($event1->newTier->value)->toBe('silver');

    // Silver to Gold
    $event2 = new LevelUp($user, UserTier::SILVER, UserTier::GOLD);
    expect($event2->oldTier->value)->toBe('silver')
        ->and($event2->newTier->value)->toBe('gold');

    // Gold to Platinum
    $event3 = new LevelUp($user, UserTier::GOLD, UserTier::PLATINUM);
    expect($event3->oldTier->value)->toBe('gold')
        ->and($event3->newTier->value)->toBe('platinum');
});
