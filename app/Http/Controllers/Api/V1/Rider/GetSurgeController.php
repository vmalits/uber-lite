<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Services\Ride\SurgePricingService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Current surge pricing multiplier.')]
#[Response(status: 401, description: 'Unauthorized.')]
final class GetSurgeController extends Controller
{
    public function __construct(
        private readonly SurgePricingService $surgeService,
    ) {}

    public function __invoke(): JsonResponse
    {
        $surge = $this->surgeService->getCurrentSurge();

        return ApiResponse::success($surge);
    }
}
