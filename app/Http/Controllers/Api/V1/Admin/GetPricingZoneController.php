<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\PricingZone\PricingZoneData;
use App\Http\Controllers\Controller;
use App\Models\PricingZone;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Pricing Zone', 'Get details of a specific pricing zone')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetPricingZoneController extends Controller
{
    public function __invoke(PricingZone $zone): JsonResponse
    {
        $this->authorize('view', $zone);

        return ApiResponse::success(
            data: PricingZoneData::fromModel($zone),
        );
    }
}
