<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\CreateRideMessageAction;
use App\Data\Ride\RideMessageData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Ride\CreateRideMessageRequest;
use App\Models\Ride;
use App\Models\RideMessage;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Throwable;

#[Group('Ride')]
#[Endpoint('Send Ride Message', 'Send a chat message in a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Message sent successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Validation failed.')]
final class CreateRideMessageController extends Controller
{
    public function __construct(
        private readonly CreateRideMessageAction $createRideMessageAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(
        CreateRideMessageRequest $request,
        Ride $ride,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $this->authorize('create', [RideMessage::class, $ride]);

        $message = $this->createRideMessageAction->handle(
            $user,
            $ride,
            $request->toData(),
        );

        return ApiResponse::created(
            RideMessageData::fromModel($message),
            __('messages.ride.message_sent'),
        );
    }
}
