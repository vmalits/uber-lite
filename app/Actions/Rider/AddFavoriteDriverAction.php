<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\AddFavoriteDriverData;
use App\Models\FavoriteDriver;
use App\Models\User;

final readonly class AddFavoriteDriverAction
{
    public function handle(User $user, AddFavoriteDriverData $data): FavoriteDriver
    {
        return FavoriteDriver::query()->create([
            'user_id'   => $user->id,
            'driver_id' => $data->driver_id,
        ]);
    }
}
