<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\PricingZone\PricingZoneData;
use App\Http\Controllers\Controller;
use App\Models\PricingZone;
use App\Queries\Admin\GetPricingZonesQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Pricing Zones', 'Get a paginated list of all pricing zones')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetPricingZonesController extends Controller
{
    public function __construct(
        private readonly GetPricingZonesQueryInterface $query,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', PricingZone::class);

        $perPage = $request->integer('per_page', 15);

        $zones = $this->query->execute($perPage);

        return ApiResponse::success(
            data: PricingZoneData::collect($zones),
        );
    }
}
