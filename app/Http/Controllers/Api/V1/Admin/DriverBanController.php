<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\BanDriverAction;
use App\Data\Admin\DriverBanData;
use App\Exceptions\Driver\DriverAlreadyBannedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\BanDriverRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Throwable;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver banned successfully.')]
#[Response(status: 409, description: 'Driver with id [%s] is already banned')]
#[Response(status: 403, description: 'Unauthorized – not an admin')]
#[Response(status: 404, description: 'Driver not found')]
final class DriverBanController extends Controller
{
    public function __construct(
        private readonly BanDriverAction $banDriver,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(User $driver, BanDriverRequest $request): JsonResponse
    {
        try {
            $dto = $request->toBanDriverData();
            $updatedBan = $this->banDriver->handle($driver, $dto);

            return ApiResponse::success(
                data: DriverBanData::fromModel($updatedBan),
                message: 'Driver banned successfully.',
            );
        } catch (DriverAlreadyBannedException $e) {
            return ApiResponse::error(message: $e->getMessage(), status: 409);
        }
    }
}
