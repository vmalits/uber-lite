<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UnbanDriverAction;
use App\Data\Admin\DriverBanData;
use App\Exceptions\Driver\DriverNotBannedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\UnbanDriverRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200)]
#[Response(status: 409, description: 'Driver is not currently banned')]
#[Response(status: 403, description: 'Unauthorized – not an admin')]
#[Response(status: 404, description: 'Driver not found')]
final class DriverUnbanController extends Controller
{
    public function __construct(private readonly UnbanDriverAction $unbanDriver,
    ) {}

    public function __invoke(User $driver, UnbanDriverRequest $request): JsonResponse
    {
        try {
            $data = $request->toUnbanDriverData();

            $updatedBan = $this->unbanDriver->handle($driver, $data);

            return ApiResponse::success(
                data: DriverBanData::from($updatedBan),
                message: 'Driver has been successfully unbanned.',
            );
        } catch (DriverNotBannedException $e) {
            return ApiResponse::error(message: $e->getMessage(), status: 409);
        }
    }
}
