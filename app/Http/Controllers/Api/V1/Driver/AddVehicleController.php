<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\CreateVehicleAction;
use App\Data\Vehicle\VehicleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Vehicle\StoreVehicleRequest;
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
#[Response(status: 201, description: 'Vehicle added successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 422, description: 'Validation errors')]
final class AddVehicleController extends Controller
{
    public function __construct(
        private readonly CreateVehicleAction $createVehicle,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        StoreVehicleRequest $request,
    ): JsonResponse {
        $this->authorize('create', Vehicle::class);

        $dto = $request->toDto();

        $vehicle = $this->createVehicle->handle($user, $dto);

        return ApiResponse::created(
            data: VehicleData::fromModel($vehicle),
            message: __('messages.vehicle.added'),
        );
    }
}
