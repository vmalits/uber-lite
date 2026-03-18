<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Actions\Notification\MarkNotificationAsReadAction;
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
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Notifications')]
#[Endpoint('Mark as Read', 'Mark a single notification as read')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(name: 'notification', type: 'string', description: 'ULID of the notification.', required: true)]
#[Response(status: 200, description: 'Notification marked as read')]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 404, description: 'Notification not found')]
final class MarkAsReadController extends Controller
{
    public function __construct(
        private readonly MarkNotificationAsReadAction $markNotificationAsReadAction,
    ) {}

    public function __invoke(#[CurrentUser] User $user, string $notification): JsonResponse
    {
        $this->markNotificationAsReadAction->handle($user, $notification);

        return ApiResponse::success(message: __('messages.notifications.marked_as_read'));
    }
}
