<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Enums\BanSource;
use App\Enums\DriverBanType;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class CreateBanData extends Data
{
    public function __construct(
        public readonly string $reason,
        public readonly DriverBanType $banType,
        public readonly BanSource $banSource,
        public readonly ?string $bannedBy = null,
        public readonly ?Carbon $expiresAt = null,
        public readonly ?string $externalId = null,
    ) {}

}
