<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Device;

use App\Actions\Device\DeleteDeviceTokenAction;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Device Tokens')]
#[Endpoint('Delete Device', 'Unregister a device token')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class DeleteDeviceTokenController extends Controller
{
    public function __construct(
        private readonly DeleteDeviceTokenAction $deleteDeviceToken,
    ) {}

    public function __invoke(DeviceToken $deviceToken): JsonResponse
    {
        $this->authorize('delete', $deviceToken);

        $this->deleteDeviceToken->handle($deviceToken);

        return ApiResponse::success(
            message: __('messages.device.deleted'),
        );
    }
}
