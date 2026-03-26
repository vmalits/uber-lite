<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Enums\AnnouncementTarget;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class CreateAnnouncementData extends Data
{
    public function __construct(
        public string $title,
        public string $body,
        public AnnouncementTarget $target,
        public bool $is_active,
        #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d H:i:s', 'Y-m-d\TH:i:sP'])]
        public ?CarbonImmutable $published_at,
        #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d H:i:s', 'Y-m-d\TH:i:sP'])]
        public ?CarbonImmutable $expires_at,
    ) {}
}
