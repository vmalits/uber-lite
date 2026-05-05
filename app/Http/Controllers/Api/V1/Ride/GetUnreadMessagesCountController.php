<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Data\Ride\UnreadMessagesCountData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\RideMessage;
use App\Models\User;
use App\Queries\Ride\GetUnreadMessagesCountQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Ride Messages')]
#[Endpoint('Get Unread Count', 'Get the count of unread messages for a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Unread message count')]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Ride not found')]
final class GetUnreadMessagesCountController extends Controller
{
    public function __construct(
        private readonly GetUnreadMessagesCountQueryInterface $getUnreadMessagesCountQuery,
    ) {}

    public function __invoke(#[CurrentUser] User $user, Ride $ride): JsonResponse
    {
        $this->authorize('unreadCount', [RideMessage::class, $ride]);

        $count = $this->getUnreadMessagesCountQuery->execute($ride, $user);

        return ApiResponse::success(UnreadMessagesCountData::from(['count' => $count]));
    }
}
