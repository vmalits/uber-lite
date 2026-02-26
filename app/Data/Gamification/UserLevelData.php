<?php

declare(strict_types=1);

namespace App\Data\Gamification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class UserLevelData extends Data
{
    public function __construct(
        public int $level,
        public int $xp,
        public string $tier,
        #[MapName('xp_to_next_tier')]
        public ?int $xpToNextTier,
        #[MapName('next_tier')]
        public ?string $nextTier,
        #[MapName('tier_threshold')]
        public int $tierThreshold,
    ) {}
}
