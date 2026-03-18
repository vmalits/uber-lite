<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Actions\Notification\MarkAllNotificationsAsReadAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Notifications')]
#[Endpoint('Mark All as Read', 'Mark all unread notifications as read')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'All notifications marked as read')]
#[Response(status: 401, description: 'Unauthorized')]
final class MarkAllAsReadController extends Controller
{
    public function __construct(
        private readonly MarkAllNotificationsAsReadAction $markAllNotificationsAsReadAction,
    ) {}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $count = $this->markAllNotificationsAsReadAction->handle($user);

        return ApiResponse::success([
            'marked_count' => $count,
        ], __('messages.notifications.all_marked_as_read'));
    }
}
