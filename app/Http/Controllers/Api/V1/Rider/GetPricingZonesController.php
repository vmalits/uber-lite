<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Services\Ride\PricingZonesService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Pricing Zones', 'Get active pricing zones information')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'List of pricing zones with surge multipliers.')]
#[Response(status: 401, description: 'Unauthorized.')]
final class GetPricingZonesController extends Controller
{
    public function __construct(
        private readonly PricingZonesService $zonesService,
    ) {}

    public function __invoke(): JsonResponse
    {
        $zones = $this->zonesService->getActiveZones();

        return ApiResponse::success([
            'zones' => $zones,
            'total' => \count($zones),
        ]);
    }
}
