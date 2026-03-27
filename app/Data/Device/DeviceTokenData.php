<?php

declare(strict_types=1);

namespace App\Data\Device;

use App\Enums\DevicePlatform;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class DeviceTokenData extends Data
{
    public function __construct(
        public string $id,
        public DevicePlatform $platform,
        public ?string $deviceName,
        public ?string $appVersion,
    ) {}
}
