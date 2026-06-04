<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\BlockedDriverData;
use App\Models\BlockedDriver;
use App\Models\User;

final readonly class BlockDriverAction
{
    public function handle(User $rider, BlockedDriverData $data): BlockedDriver
    {
        return BlockedDriver::query()->create([
            'rider_id'  => $rider->id,
            'driver_id' => $data->driver_id,
        ]);
    }
}
