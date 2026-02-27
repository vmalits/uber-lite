<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Safety;

use App\Actions\Safety\AddEmergencyContactAction;
use App\Data\Safety\EmergencyContactData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Safety\AddEmergencyContactRequest;
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
#[Response(status: 201, description: 'Emergency contact added successfully')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider/driver role or profile incomplete')]
#[Response(status: 422, description: 'Validation errors')]
final class AddEmergencyContactController extends Controller
{
    public function __construct(
        private readonly AddEmergencyContactAction $addEmergencyContactAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        AddEmergencyContactRequest $request,
    ): JsonResponse {
        $contact = $this->addEmergencyContactAction->handle(
            $user,
            $request->toData(),
        );

        return ApiResponse::created(
            data: EmergencyContactData::fromModel($contact),
            message: __('messages.safety.contact_added'),
        );
    }
}
