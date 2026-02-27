<?php

declare(strict_types=1);

use App\Actions\Gamification\UpdateUserTierAction;

it('returns bronze for xp below 500', function () {
    $action = app(UpdateUserTierAction::class);

    expect($action->handle(0))->toBe('bronze')
        ->and($action->handle(100))->toBe('bronze')
        ->and($action->handle(499))->toBe('bronze');
});

it('returns silver for xp between 500 and 1999', function () {
    $action = app(UpdateUserTierAction::class);

    expect($action->handle(500))->toBe('silver')
        ->and($action->handle(1000))->toBe('silver')
        ->and($action->handle(1999))->toBe('silver');
});

it('returns gold for xp between 2000 and 4999', function () {
    $action = app(UpdateUserTierAction::class);

    expect($action->handle(2000))->toBe('gold')
        ->and($action->handle(3000))->toBe('gold')
        ->and($action->handle(4999))->toBe('gold');
});

it('returns platinum for xp 5000 and above', function () {
    $action = app(UpdateUserTierAction::class);

    expect($action->handle(5000))->toBe('platinum')
        ->and($action->handle(10000))->toBe('platinum')
        ->and($action->handle(100000))->toBe('platinum');
});
