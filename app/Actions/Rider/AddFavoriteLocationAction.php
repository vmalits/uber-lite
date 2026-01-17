<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateFavoriteLocationData;
use App\Models\FavoriteLocation;
use App\Models\User;

final readonly class AddFavoriteLocationAction
{
    public function handle(User $user, CreateFavoriteLocationData $data): FavoriteLocation
    {
        return FavoriteLocation::query()->create([
            'user_id' => $user->id,
            'name'    => $data->name,
            'lat'     => $data->lat,
            'lng'     => $data->lng,
            'address' => $data->address,
        ]);
    }
}
