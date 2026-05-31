<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdatePricingZoneAction;
use App\Data\PricingZone\PricingZoneData;
use App\Data\PricingZone\PricingZoneInputData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PricingZone\PricingZoneRequest;
use App\Models\PricingZone;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Update Pricing Zone', 'Update an existing pricing zone')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Pricing zone updated successfully.')]
final class UpdatePricingZoneController extends Controller
{
    public function __construct(
        private readonly UpdatePricingZoneAction $updatePricingZone,
    ) {}

    public function __invoke(PricingZoneRequest $request, PricingZone $zone): JsonResponse
    {
        $this->authorize('update', $zone);

        $zone = $this->updatePricingZone->handle(
            zone: $zone,
            data: PricingZoneInputData::from($request->validated()),
        );

        return ApiResponse::success(
            data: PricingZoneData::fromModel($zone),
            message: __('messages.pricing_zone.updated'),
        );
    }
}
