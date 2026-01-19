<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Presenters\Admin\DriverProfilePresenterInterface;
use App\Queries\Admin\GetDriverQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver details with statistics retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'Driver not found.')]
final class GetDriverController extends Controller
{
    public function __construct(
        private readonly GetDriverQueryInterface $query,
        private readonly DriverProfilePresenterInterface $presenter,
    ) {}

    public function __invoke(string $driver): JsonResponse
    {
        $driverModel = $this->query->execute($driver);

        $this->authorize('view', $driverModel);

        return ApiResponse::success($this->presenter->present($driverModel));
    }
}
