<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\GetRideSplitsAction;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Ride')]
#[Endpoint('Get Ride Splits', 'Get details of ride fare splits')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[Response(status: 200, description: 'List of ride split participants.')]
#[Response(status: 401, description: 'Unauthenticated.')]
#[Response(status: 403, description: 'Forbidden - You can only view splits of your own rides.')]
#[Response(status: 404, description: 'Ride not found.')]
final class GetRideSplitsController extends Controller
{
    public function __construct(
        private readonly GetRideSplitsAction $getRideSplits,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('split', $ride);

        $splits = $this->getRideSplits->handle($ride);

        return ApiResponse::success([
            'ride_id' => $ride->id,
            'splits'  => $splits,
        ]);
    }
}
