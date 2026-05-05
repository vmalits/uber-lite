<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Device;

use App\Actions\Device\UpdateDeviceTokenAction;
use App\Data\Device\DeviceTokenData;
use App\Data\Device\UpdateDeviceTokenData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Device\UpdateDeviceTokenRequest;
use App\Models\DeviceToken;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Device Tokens')]
#[Endpoint('Update Device', 'Update device token metadata')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Device token updated successfully.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Device token not found.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateDeviceTokenController extends Controller
{
    public function __construct(
        private readonly UpdateDeviceTokenAction $updateDeviceToken,
    ) {}

    public function __invoke(UpdateDeviceTokenRequest $request, DeviceToken $deviceToken): JsonResponse
    {
        $this->authorize('update', $deviceToken);

        $deviceToken = $this->updateDeviceToken->handle(
            $deviceToken,
            UpdateDeviceTokenData::from($request->validated()),
        );

        return ApiResponse::success(
            data: DeviceTokenData::from($deviceToken),
            message: __('messages.device.updated'),
        );
    }
}
