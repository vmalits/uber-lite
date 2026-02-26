<?php

declare(strict_types=1);

namespace App\Data\Gamification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class LevelUpData extends Data
{
    public function __construct(
        #[MapName('old_tier')]
        public string $oldTier,
        #[MapName('new_tier')]
        public string $newTier,
        public int $level,
        public int $xp,
    ) {}
}
