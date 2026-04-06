<?php

declare(strict_types=1);

namespace App\Services\Driver;

final readonly class SyncResult
{
    public function __construct(
        public int $wentOnline,
        public int $wentOffline,
        public int $candidatesOnline,
        public int $candidatesOffline,
    ) {}
}
