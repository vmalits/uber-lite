<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\UpdateFavoriteLocationData;
use App\Models\FavoriteLocation;

final readonly class UpdateFavoriteLocationAction
{
    public function handle(FavoriteLocation $favorite, UpdateFavoriteLocationData $data): FavoriteLocation
    {
        $updateData = [];

        if ($data->name !== null) {
            $updateData['name'] = $data->name;
        }

        if ($data->lat !== null) {
            $updateData['lat'] = $data->lat;
        }

        if ($data->lng !== null) {
            $updateData['lng'] = $data->lng;
        }

        if ($data->address !== null) {
            $updateData['address'] = $data->address;
        }

        if ($updateData !== []) {
            $favorite->update($updateData);
        }

        return $favorite;
    }
}
