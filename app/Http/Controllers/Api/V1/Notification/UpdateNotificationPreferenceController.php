<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Notification;

use App\Actions\Notification\UpdateNotificationPreferenceAction;
use App\Data\Notification\NotificationPreferenceData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Notification\UpdateNotificationPreferenceRequest;
use App\Models\User;
use App\Queries\Notification\GetNotificationPreferenceQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Notifications')]
#[Endpoint('Update Preferences', 'Update notification preferences for the current user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class UpdateNotificationPreferenceController extends Controller
{
    public function __construct(
        private readonly GetNotificationPreferenceQueryInterface $query,
        private readonly UpdateNotificationPreferenceAction $updateAction,
    ) {}

    public function __invoke(UpdateNotificationPreferenceRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $preference = $this->query->execute($user->id);

        /** @var array<string, bool> $validated */
        $validated = $request->validated();

        $preference = $this->updateAction->handle($preference, $validated);

        return ApiResponse::success(
            data: NotificationPreferenceData::fromModel($preference),
            message: __('messages.notification.preferences_updated'),
        );
    }
}
