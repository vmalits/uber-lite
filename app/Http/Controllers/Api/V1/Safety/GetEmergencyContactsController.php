<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Safety;

use App\Data\Safety\EmergencyContactData;
use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
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
#[Response(status: 200, description: 'List of emergency contacts')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider/driver role or profile incomplete')]
final class GetEmergencyContactsController extends Controller
{
    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $contacts = $user->emergencyContacts()
            ->orderByDesc('is_primary')
            ->orderBy('created_at')
            ->get();

        $data = $contacts->map(
            fn (EmergencyContact $contact): EmergencyContactData => EmergencyContactData::fromModel($contact),
        );

        return ApiResponse::success($data);
    }
}
