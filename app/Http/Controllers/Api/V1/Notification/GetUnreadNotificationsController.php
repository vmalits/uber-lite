<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Data\Notification\NotificationData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Notification\GetNotificationsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Notifications')]
#[Endpoint('Get Unread Notifications', 'Get paginated list of unread user notifications')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('per_page', 'int', description: 'Items per page', required: false, example: 15)]
#[Response(status: 200, description: 'Paginated list of unread notifications')]
#[Response(status: 401, description: 'Unauthorized')]
final class GetUnreadNotificationsController extends Controller
{
    public function __construct(
        private readonly GetNotificationsQueryInterface $getNotificationsQuery,
    ) {}

    public function __invoke(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, DatabaseNotification> $notifications */
        $notifications = $this->getNotificationsQuery->execute($user, $perPage, unreadOnly: true);

        $notifications->through(
            fn (DatabaseNotification $notification): NotificationData => NotificationData::from([
                'id'         => $notification->id,
                'type'       => $notification->type,
                'title'      => $notification->data['title'] ?? null,
                'body'       => $notification->data['body'] ?? null,
                'data'       => $notification->data,
                'read_at'    => $notification->read_at,
                'created_at' => $notification->created_at,
            ]),
        );

        return ApiResponse::success($notifications);
    }
}
