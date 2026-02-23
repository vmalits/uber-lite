<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\FavoriteRouteData;
use App\Models\FavoriteRoute;
use App\Models\User;
use Throwable;

final readonly class AddFavoriteRouteAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, FavoriteRouteData $data): FavoriteRoute
    {
        return FavoriteRoute::query()->create([
            'user_id'             => $user->id,
            'name'                => $data->name,
            'origin_address'      => $data->originAddress,
            'origin_lat'          => $data->originLat,
            'origin_lng'          => $data->originLng,
            'destination_address' => $data->destinationAddress,
            'destination_lat'     => $data->destinationLat,
            'destination_lng'     => $data->destinationLng,
            'type'                => $data->type,
        ]);
    }
}
