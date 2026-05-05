<?php

declare(strict_types=1);

namespace App\Data\Device;

use App\Enums\DevicePlatform;
use Spatie\LaravelData\Data;

final class UpdateDeviceTokenData extends Data
{
    public function __construct(
        public ?DevicePlatform $platform,
        public ?string $device_name,
        public ?string $app_version,
    ) {}
}
