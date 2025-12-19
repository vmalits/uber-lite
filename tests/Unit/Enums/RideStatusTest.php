<?php

declare(strict_types=1);

use App\Enums\RideStatus;

it('allows valid transitions', function (RideStatus $current, RideStatus $target) {
    expect($current->canTransitionTo($target))->toBeTrue();
})->with([
    [RideStatus::PENDING, RideStatus::ACCEPTED],
    [RideStatus::PENDING, RideStatus::CANCELLED],
    [RideStatus::ACCEPTED, RideStatus::ON_THE_WAY],
    [RideStatus::ACCEPTED, RideStatus::CANCELLED],
    [RideStatus::ON_THE_WAY, RideStatus::ARRIVED],
    [RideStatus::ON_THE_WAY, RideStatus::CANCELLED],
    [RideStatus::ARRIVED, RideStatus::STARTED],
    [RideStatus::ARRIVED, RideStatus::CANCELLED],
    [RideStatus::STARTED, RideStatus::COMPLETED],
    [RideStatus::STARTED, RideStatus::CANCELLED],
]);

it('disallows invalid transitions', function (RideStatus $current, RideStatus $target) {
    expect($current->canTransitionTo($target))->toBeFalse();
})->with([
    [RideStatus::PENDING, RideStatus::COMPLETED],
    [RideStatus::COMPLETED, RideStatus::PENDING],
    [RideStatus::CANCELLED, RideStatus::PENDING],
    [RideStatus::COMPLETED, RideStatus::CANCELLED],
]);

it('correctly identifies status groups', function (RideStatus $status, bool $active, bool $inProgress, bool $final) {
    expect($status->isActive())->toBe($active)
        ->and($status->isInProgress())->toBe($inProgress)
        ->and($status->isFinal())->toBe($final);
})->with([
    [RideStatus::PENDING, true, false, false],
    [RideStatus::ACCEPTED, true, true, false],
    [RideStatus::ON_THE_WAY, true, true, false],
    [RideStatus::ARRIVED, true, true, false],
    [RideStatus::STARTED, true, true, false],
    [RideStatus::COMPLETED, false, false, true],
    [RideStatus::CANCELLED, false, false, true],
]);

it('correctly identifies completed status', function () {
    expect(RideStatus::COMPLETED->isCompleted())->toBeTrue()
        ->and(RideStatus::PENDING->isCompleted())->toBeFalse();
});

it('correctly identifies cancelled status', function () {
    expect(RideStatus::CANCELLED->isCancelled())->toBeTrue()
        ->and(RideStatus::PENDING->isCancelled())->toBeFalse();
});
