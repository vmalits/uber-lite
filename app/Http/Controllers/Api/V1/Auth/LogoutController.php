<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Auth
 *
 * Logout
 *
 * Revoke the current access token and logout the authenticated user.
 *
 * Requires Bearer token (issued after OTP verification).
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 200 {"message":"Logged out successfully."}
 */
final class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return ApiResponse::success(message: 'Logged out successfully.');
    }
}
