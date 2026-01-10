<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Presenters\User\UserProfilePresenter;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Rider profile retrieved successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class ProfileController extends Controller
{
    public function __construct(private UserProfilePresenter $presenter) {}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $data = $this->presenter->present($user);

        return ApiResponse::success(data: $data);
    }
}
