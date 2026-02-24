<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\FavoriteRoute;

final readonly class DeleteFavoriteRouteAction
{
    public function handle(FavoriteRoute $route): void
    {
        $route->delete();
    }
}
