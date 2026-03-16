<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Safety;

use App\Actions\Safety\UpdateEmergencyContactAction;
use App\Data\Safety\EmergencyContactData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Safety\UpdateEmergencyContactRequest;
use App\Models\EmergencyContact;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Safety')]
#[Endpoint('Update Emergency Contact', 'Update an existing emergency contact')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'contact',
    type: 'string',
    description: 'ULID of the emergency contact.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'Emergency contact updated successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Emergency contact not found.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateEmergencyContactController extends Controller
{
    public function __construct(
        private readonly UpdateEmergencyContactAction $updateEmergencyContact,
    ) {}

    public function __invoke(
        EmergencyContact $contact,
        UpdateEmergencyContactRequest $request,
    ): JsonResponse {
        $this->authorize('update', $contact);

        $contact = $this->updateEmergencyContact->handle($contact, $request->toData());

        return ApiResponse::success(
            data: EmergencyContactData::fromModel($contact),
        );
    }
}
