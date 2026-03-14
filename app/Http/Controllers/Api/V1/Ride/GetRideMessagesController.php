<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Data\Ride\RideMessageData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\RideMessage;
use App\Queries\Ride\GetRideMessagesQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Ride')]
#[Endpoint('Get Ride Messages', 'Get paginated list of chat messages for a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Ride messages retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Ride not found.')]
final class GetRideMessagesController extends Controller
{
    public function __construct(
        private readonly GetRideMessagesQueryInterface $getRideMessagesQuery,
    ) {}

    public function __invoke(Request $request, Ride $ride): JsonResponse
    {
        $this->authorize('viewAny', [RideMessage::class, $ride]);

        $perPage = PaginationHelper::perPage($request, default: 50, max: 100);

        /** @var LengthAwarePaginator<int, RideMessage> $messages */
        $messages = $this->getRideMessagesQuery->execute($ride, $perPage);

        $messages->through(
            fn (RideMessage $message): RideMessageData => RideMessageData::fromModel($message),
        );

        /** @var LengthAwarePaginator<int, mixed> $messages */
        return ApiResponse::success($messages);
    }
}
