<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\CreateRideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetEstimateRequest;
use App\Models\Ride;
use App\Services\Ride\RideEstimationService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Ride estimate calculated successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
#[Response(status: 403, description: 'Profile not completed or active ride exists.')]
final class GetEstimateController extends Controller
{
    public function __construct(
        private readonly RideEstimationService $estimationService,
    ) {}

    public function __invoke(GetEstimateRequest $request): JsonResponse
    {
        $this->authorize('create', Ride::class);

        $createRideData = CreateRideData::from($request->validated());
        $estimate = $this->estimationService->calculateEstimates($createRideData);

        return ApiResponse::success(
            data: $estimate,
            message: __('messages.ride.estimate_calculated'),
        );
    }
}
