<?php

declare(strict_types=1);

namespace App\Data\Device;

use App\Enums\DevicePlatform;
use Spatie\LaravelData\Data;

final class CreateDeviceTokenData extends Data
{
    public function __construct(
        public DevicePlatform $platform,
        public string $token,
        public ?string $device_name,
        public ?string $app_version,
    ) {}
}
