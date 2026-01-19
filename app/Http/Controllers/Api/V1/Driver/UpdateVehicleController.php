<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\UpdateVehicleAction;
use App\Data\Vehicle\VehicleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Vehicle\UpdateVehicleRequest;
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
#[Response(status: 200, description: 'Vehicle updated successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Vehicle not found')]
#[Response(status: 422, description: 'Validation errors')]
final class UpdateVehicleController extends Controller
{
    public function __construct(
        private readonly UpdateVehicleAction $updateVehicle,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        UpdateVehicleRequest $request,
        Vehicle $vehicle,
    ): JsonResponse {
        $this->authorize('update', $vehicle);

        $dto = $request->toDto();

        $vehicle = $this->updateVehicle->handle($vehicle, $dto);

        return ApiResponse::success(
            data: VehicleData::fromModel($vehicle),
            message: __('messages.success.updated'),
        );
    }
}
