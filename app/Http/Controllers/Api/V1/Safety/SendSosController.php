<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Safety;

use App\Actions\Safety\SendSosAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Safety\SendSosRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Safety')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'SOS alert sent to emergency contacts')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider/driver role or profile incomplete')]
#[Response(status: 400, description: 'No emergency contacts found')]
#[Response(status: 422, description: 'Validation errors')]
final class SendSosController extends Controller
{
    public function __construct(
        private readonly SendSosAction $sendSosAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        SendSosRequest $request,
    ): JsonResponse {
        $sent = $this->sendSosAction->handle(
            $user,
            $request->toData(),
        );

        if (! $sent) {
            return ApiResponse::error(
                message: __('messages.safety.no_emergency_contacts'),
                status: 400,
            );
        }

        return ApiResponse::success(
            message: __('messages.safety.sos_sent'),
        );
    }
}
