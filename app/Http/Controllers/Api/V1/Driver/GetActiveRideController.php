<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetActiveRideQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Active ride retrieved successfully.')]
#[Response(status: 200, description: 'No active ride found.')]
#[Response(status: 403, description: 'Forbidden. Profile step isn\'t completed.')]
final class GetActiveRideController extends Controller
{
    public function __construct(
        private readonly GetActiveRideQueryInterface $getActiveRideQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $activeRide = $this->getActiveRideQuery->execute($user);

        if ($activeRide === null) {
            return ApiResponse::success(
                data: null,
                message: 'No active ride found.',
            );
        }

        return ApiResponse::success(
            data: RideData::fromModel($activeRide),
        );
    }
}
