<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Profile\UpdateProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Profile\UpdateProfileRequest;
use App\Models\User;
use App\Presenters\User\UserProfilePresenter;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver profile updated successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 422, description: 'Validation errors')]
final class UpdateProfileController extends Controller
{
    public function __construct(
        private readonly UpdateProfile $updateProfile,
        private readonly UserProfilePresenter $presenter,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        UpdateProfileRequest $request,
    ): JsonResponse {
        $dto = $request->toDto();

        $user = $this->updateProfile->handle($user, $dto);

        return ApiResponse::success(
            data: $this->presenter->present($user),
            message: 'Profile updated successfully.',
        );
    }
}
