<?php

declare(strict_types=1);

namespace App\Enums;

use function in_array;

enum RideStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case ON_THE_WAY = 'on_the_way';
    case ARRIVED = 'arrived';
    case STARTED = 'started';

    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function isActive(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::ACCEPTED,
            self::ON_THE_WAY,
            self::ARRIVED,
            self::STARTED,
        ], true);
    }

    public function isInProgress(): bool
    {
        return in_array($this, [
            self::ACCEPTED,
            self::ON_THE_WAY,
            self::ARRIVED,
            self::STARTED,
        ], true);
    }

    public function isCompleted(): bool
    {
        return $this === self::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function isFinal(): bool
    {
        return in_array($this, [
            self::COMPLETED,
            self::CANCELLED,
        ], true);
    }

    /**
     * @return array<int, self>
     */
    public function getAllowedTransitions(): array
    {
        return match ($this) {
            self::PENDING    => [self::ACCEPTED, self::CANCELLED],
            self::ACCEPTED   => [self::ON_THE_WAY, self::CANCELLED],
            self::ON_THE_WAY => [self::ARRIVED, self::CANCELLED],
            self::ARRIVED    => [self::STARTED, self::CANCELLED],
            self::STARTED    => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED, self::CANCELLED => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->getAllowedTransitions(), true);
    }
}
