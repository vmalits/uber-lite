<?php

declare(strict_types=1);

namespace App\Actions\Device;

use App\Models\DeviceToken;

final readonly class DeleteDeviceTokenAction
{
    public function handle(DeviceToken $deviceToken): bool
    {
        return (bool) $deviceToken->delete();
    }
}
