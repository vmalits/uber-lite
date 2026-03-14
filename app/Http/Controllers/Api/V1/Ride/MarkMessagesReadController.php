<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\MarkMessagesReadAction;
use App\Http\Controllers\Controller;
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

#[Group('Ride')]
#[Endpoint('Mark Messages as Read', 'Mark all unread messages in a ride as read')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Messages marked as read.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Ride not found.')]
final class MarkMessagesReadController extends Controller
{
    public function __construct(
        private readonly MarkMessagesReadAction $markMessagesReadAction,
    ) {}

    public function __invoke(Ride $ride, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('markAsRead', [RideMessage::class, $ride]);

        $count = $this->markMessagesReadAction->handle($user, $ride);

        return ApiResponse::success([
            'marked_count' => $count,
        ]);
    }
}
