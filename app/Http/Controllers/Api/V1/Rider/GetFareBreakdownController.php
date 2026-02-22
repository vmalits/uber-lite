<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Rider\GetFareBreakdownQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Fare breakdown retrieved successfully.')]
final class GetFareBreakdownController extends Controller
{
    public function __construct(
        private readonly GetFareBreakdownQueryInterface $getFareBreakdownQuery,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('view', $ride);

        $breakdown = $this->getFareBreakdownQuery->execute($ride);

        return ApiResponse::success($breakdown);
    }
}
