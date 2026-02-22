<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Queries\Driver\GetHeatmapQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Heatmap data retrieved successfully.')]
final class GetHeatmapController extends Controller
{
    public function __construct(
        private readonly GetHeatmapQueryInterface $getHeatmapQuery,
    ) {}

    public function __invoke(): JsonResponse
    {
        $points = $this->getHeatmapQuery->execute();

        return ApiResponse::success($points);
    }
}
