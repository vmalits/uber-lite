<?php

declare(strict_types=1);

namespace App\Actions\Device;

use App\Data\Device\UpdateDeviceTokenData;
use App\Models\DeviceToken;

final readonly class UpdateDeviceTokenAction
{
    public function handle(DeviceToken $deviceToken, UpdateDeviceTokenData $data): DeviceToken
    {
        $updateData = [];

        if ($data->platform !== null) {
            $updateData['platform'] = $data->platform;
        }

        if ($data->device_name !== null) {
            $updateData['device_name'] = $data->device_name;
        }

        if ($data->app_version !== null) {
            $updateData['app_version'] = $data->app_version;
        }

        if ($updateData !== []) {
            $updateData['last_used_at'] = now();
            $deviceToken->update($updateData);
        }

        return $deviceToken->refresh();
    }
}
