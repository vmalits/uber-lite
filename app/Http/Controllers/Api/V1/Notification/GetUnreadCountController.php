<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Data\Notification\UnreadCountData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Notification\GetUnreadNotificationsCountQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Notifications')]
#[Endpoint('Get Unread Count', 'Get the count of unread notifications')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Unread notification count')]
#[Response(status: 401, description: 'Unauthorized')]
final class GetUnreadCountController extends Controller
{
    public function __construct(
        private readonly GetUnreadNotificationsCountQueryInterface $getUnreadNotificationsCountQuery,
    ) {}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $count = $this->getUnreadNotificationsCountQuery->execute($user);

        return ApiResponse::success(UnreadCountData::from(['count' => $count]));
    }
}
