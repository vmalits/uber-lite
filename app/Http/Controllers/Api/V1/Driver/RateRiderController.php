<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\RateRiderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\RateRiderRequest;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Rate Rider', 'Rate a rider after a completed ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Rider rated successfully.')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Ride not found')]
#[Response(status: 422, description: 'Validation failed')]
final class RateRiderController extends Controller
{
    public function __construct(
        private readonly RateRiderAction $rateRider,
    ) {}

    public function __invoke(RateRiderRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('rateRider', $ride);

        $rating = $this->rateRider->handle(
            $ride,
            $request->toDriverRatingData(),
        );

        return ApiResponse::success(
            data: $rating,
            message: __('messages.ride.rated'),
        );
    }
}
