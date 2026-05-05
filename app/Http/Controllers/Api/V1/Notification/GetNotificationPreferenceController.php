<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Data\Notification\NotificationPreferenceData;
use App\Http\Controllers\Controller;
use App\Queries\Notification\GetNotificationPreferenceQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Notifications')]
#[Endpoint('Get Preferences', 'Get notification preferences for the current user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetNotificationPreferenceController extends Controller
{
    public function __construct(
        private readonly GetNotificationPreferenceQueryInterface $query,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $preference = $this->query->execute($user->id);

        return ApiResponse::success(
            data: NotificationPreferenceData::fromModel($preference),
        );
    }
}
