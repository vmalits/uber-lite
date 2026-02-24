<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\FavoriteRoute;
use Throwable;

final readonly class UpdateFavoriteRouteAction
{
    /**
     * @param array<string, mixed> $data
     *
     * @throws Throwable
     */
    public function handle(FavoriteRoute $route, array $data): FavoriteRoute
    {
        $route->fill($data);
        $route->saveOrFail();

        return $route->fresh() ?? $route;
    }
}
