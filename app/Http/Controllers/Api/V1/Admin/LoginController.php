<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\AdminLogin;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AdminLoginRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Response(status: 200, description: 'Admin authenticated successfully.')]
#[Response(status: 401, description: 'Invalid credentials.')]
final class LoginController extends Controller
{
    public function __construct(
        private readonly AdminLogin $adminLogin,
    ) {}

    public function __invoke(AdminLoginRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        $responseDto = $this->adminLogin->handle($dto);

        return ApiResponse::success(
            data: $responseDto,
            message: 'Admin authenticated successfully',
        );
    }
}
