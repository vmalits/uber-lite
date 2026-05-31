<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\DeletePricingZoneAction;
use App\Http\Controllers\Controller;
use App\Models\PricingZone;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Delete Pricing Zone', 'Delete a pricing zone')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class DeletePricingZoneController extends Controller
{
    public function __construct(
        private readonly DeletePricingZoneAction $deletePricingZone,
    ) {}

    public function __invoke(PricingZone $zone): JsonResponse
    {
        $this->authorize('delete', $zone);

        $this->deletePricingZone->handle($zone);

        return ApiResponse::success(
            message: __('messages.pricing_zone.deleted'),
        );
    }
}
