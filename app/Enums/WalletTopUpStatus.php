<?php

declare(strict_types=1);

namespace App\Enums;

enum WalletTopUpStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::FAILED    => 'Failed',
        };
    }

    public function isFinal(): bool
    {
        return \in_array($this, [self::COMPLETED, self::CANCELLED, self::FAILED], true);
    }
}
