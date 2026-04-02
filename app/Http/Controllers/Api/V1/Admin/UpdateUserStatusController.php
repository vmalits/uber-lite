<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdateUserStatusAction;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\UpdateUserStatusRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Update User Status', 'Change the status of a user (active, inactive, banned)')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'User status updated successfully.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateUserStatusController extends Controller
{
    public function __construct(
        private readonly UpdateUserStatusAction $updateUserStatus,
    ) {}

    public function __invoke(UpdateUserStatusRequest $request, User $user): JsonResponse
    {
        $this->authorize('updateStatus', $user);

        /** @var string $statusValue */
        $statusValue = $request->validated('status');

        $status = UserStatus::from($statusValue);

        $user = $this->updateUserStatus->handle(
            user: $user,
            status: $status,
        );

        return ApiResponse::success(
            data: [
                'id'     => $user->id,
                'phone'  => $user->phone,
                'status' => $user->status->value,
            ],
            message: __('messages.user.status_updated'),
        );
    }
}
