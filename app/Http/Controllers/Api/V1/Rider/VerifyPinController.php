<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Verify Ride PIN', 'Verify PIN code for ride pickup')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'PIN verified successfully')]
#[Response(status: 400, description: 'PIN already verified')]
#[Response(status: 403, description: 'Not authorized to verify this ride')]
final class VerifyPinController extends Controller
{
    public function __invoke(#[CurrentUser] User $user, Ride $ride): JsonResponse
    {
        $this->authorize('verifyPin', $ride);

        if ($ride->isPinVerified()) {
            return ApiResponse::error(
                message: __('messages.ride.pin_already_verified'),
                status: 400,
            );
        }

        $ride->verifyPin();

        return ApiResponse::success(
            message: __('messages.ride.pin_verified'),
        );
    }
}
