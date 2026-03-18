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
#[Endpoint('Get Notifications', 'Get paginated list of user notifications')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('per_page', 'int', example: 15, description: 'Items per page', required: false)]
#[QueryParam('filter[type]', 'string', example: 'gamification', description: 'Filter by notification type', required: false)]
#[QueryParam('filter[read]', 'string', example: '0', description: 'Filter by read status (null = unread, any value = read)', required: false)]
#[QueryParam('sort', 'string', example: '-created_at', description: 'Sort by field (created_at, read_at)', required: false)]
#[Response(status: 200, description: 'Paginated list of notifications')]
#[Response(status: 401, description: 'Unauthorized')]
final class GetNotificationsController extends Controller
{
    public function __construct(
        private readonly GetNotificationsQueryInterface $getNotificationsQuery,
    ) {}

    public function __invoke(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, DatabaseNotification> $notifications */
        $notifications = $this->getNotificationsQuery->execute($user, $perPage);

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
