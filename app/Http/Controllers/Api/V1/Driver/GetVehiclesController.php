<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Vehicle\VehicleData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Queries\Driver\GetVehiclesQueryInterface;
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
#[Response(status: 200, description: 'List of driver vehicles')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetVehiclesController extends Controller
{
    public function __construct(
        private readonly GetVehiclesQueryInterface $getVehiclesQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $this->authorize('viewAny', Vehicle::class);

        $vehicles = $this->getVehiclesQuery->execute($user);

        /** @var array<string, mixed> $data */
        $data = VehicleData::collect($vehicles)->toArray();

        return ApiResponse::success(
            data: $data,
        );
    }
}
