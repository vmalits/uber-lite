<?php

declare(strict_types=1);

namespace App\Data\Notification;

use Spatie\LaravelData\Data;

final class UnreadCountData extends Data
{
    public function __construct(
        public int $count,
    ) {}
}
