<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Device;

use App\Actions\Device\CreateDeviceTokenAction;
use App\Data\Device\CreateDeviceTokenData;
use App\Data\Device\DeviceTokenData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Device\CreateDeviceTokenRequest;
use App\Models\DeviceToken;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Device Tokens')]
#[Endpoint('Register Device', 'Register a device for push notifications')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Device token registered successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreateDeviceTokenController extends Controller
{
    public function __construct(
        private readonly CreateDeviceTokenAction $createDeviceToken,
    ) {}

    public function __invoke(CreateDeviceTokenRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('create', DeviceToken::class);

        $deviceToken = $this->createDeviceToken->handle(
            data: CreateDeviceTokenData::from($request->validated()),
            userId: $user->id,
        );

        return ApiResponse::created(
            data: DeviceTokenData::from($deviceToken),
            message: __('messages.device.registered'),
        );
    }
}
