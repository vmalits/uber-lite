<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Vehicle\VehicleData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Vehicle retrieved successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Vehicle not found')]
final class GetVehicleController extends Controller
{
    public function __invoke(
        #[CurrentUser] User $user,
        Vehicle $vehicle,
    ): JsonResponse {
        $this->authorize('view', $vehicle);

        return ApiResponse::success(
            data: VehicleData::from($vehicle),
        );
    }
}
