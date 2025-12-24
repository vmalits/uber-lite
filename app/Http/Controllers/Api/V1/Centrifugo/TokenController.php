<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Centrifugo;

use App\Actions\Centrifugo\GenerateCentrifugoToken;
use App\Data\Centrifugo\CentrifugoTokenData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group WS
 *
 * WS Token
 *
 * Endpoint to get a JWT token for Centrifugo connection.
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 200 {
 *   "success": true,
 *   "data": {
 *     "token": "header.payload.signature"
 *   }
 * }
 */
final class TokenController extends Controller
{
    public function __construct(
        private readonly GenerateCentrifugoToken $generateToken,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $token = $this->generateToken->handle($user);

        return ApiResponse::success(
            data: new CentrifugoTokenData(token: $token),
        );
    }
}
