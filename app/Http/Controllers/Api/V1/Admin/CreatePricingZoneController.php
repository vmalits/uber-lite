<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\CreatePricingZoneAction;
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
#[Endpoint('Create Pricing Zone', 'Create a new pricing zone')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Pricing zone created successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreatePricingZoneController extends Controller
{
    public function __construct(
        private readonly CreatePricingZoneAction $createPricingZone,
    ) {}

    public function __invoke(PricingZoneRequest $request): JsonResponse
    {
        $this->authorize('create', PricingZone::class);

        $zone = $this->createPricingZone->handle(
            data: PricingZoneInputData::from($request->validated()),
        );

        return ApiResponse::created(
            data: PricingZoneData::fromModel($zone),
            message: __('messages.pricing_zone.created'),
        );
    }
}
