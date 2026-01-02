<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\UpdateLocation;
use App\Data\Driver\DriverLocationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\UpdateDriverLocationRequest;
use App\Models\User;
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
#[Response(status: 200, description: 'Driver location updated successfully.')]
#[Response(status: 403, description: 'Forbidden. Profile step isn\'t completed.')]
final class UpdateLocationController extends Controller
{
    public function __construct(
        private readonly UpdateLocation $updateLocation,
    ) {}

    public function __invoke(#[CurrentUser] User $user, UpdateDriverLocationRequest $request): JsonResponse
    {
        $locationDto = $request->toDto();

        $location = $this->updateLocation->handle(
            driver: $user,
            data: $locationDto,
        );

        return ApiResponse::success(
            data: DriverLocationData::fromModel($location),
            message: 'Driver location updated successfully.',
        );
    }
}
