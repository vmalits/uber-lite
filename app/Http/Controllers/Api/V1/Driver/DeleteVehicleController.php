<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\DeleteVehicleAction;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Delete Vehicle', 'Remove a vehicle from driver\'s account')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Vehicle deleted successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Vehicle not found')]
final class DeleteVehicleController extends Controller
{
    public function __construct(
        private readonly DeleteVehicleAction $deleteVehicle,
    ) {}

    public function __invoke(Vehicle $vehicle): JsonResponse
    {
        $this->authorize('delete', $vehicle);

        $this->deleteVehicle->handle($vehicle);

        return ApiResponse::success(
            message: __('messages.success.deleted'),
        );
    }
}
