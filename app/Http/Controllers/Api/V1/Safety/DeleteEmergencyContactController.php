<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Safety;

use App\Actions\Safety\DeleteEmergencyContactAction;
use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Safety')]
#[Endpoint('Delete Emergency Contact', 'Remove an emergency contact from user\'s list')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'contact',
    type: 'string',
    description: 'ULID of the emergency contact.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'Emergency contact deleted successfully')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - Cannot delete another users contact')]
#[Response(status: 404, description: 'Contact not found')]
final class DeleteEmergencyContactController extends Controller
{
    public function __construct(
        private readonly DeleteEmergencyContactAction $deleteEmergencyContactAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        EmergencyContact $contact,
    ): JsonResponse {
        $this->authorize('delete', $contact);

        $this->deleteEmergencyContactAction->handle($contact);

        return ApiResponse::success(
            message: __('messages.safety.contact_deleted'),
        );
    }
}
