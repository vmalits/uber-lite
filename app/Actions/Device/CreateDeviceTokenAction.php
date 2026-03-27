<?php

declare(strict_types=1);

namespace App\Actions\Device;

use App\Data\Device\CreateDeviceTokenData;
use App\Models\DeviceToken;

final readonly class CreateDeviceTokenAction
{
    public function handle(CreateDeviceTokenData $data, string $userId): DeviceToken
    {
        return DeviceToken::query()->create([
            'user_id'      => $userId,
            'platform'     => $data->platform,
            'token'        => $data->token,
            'device_name'  => $data->device_name,
            'app_version'  => $data->app_version,
            'last_used_at' => now(),
        ]);
    }
}
