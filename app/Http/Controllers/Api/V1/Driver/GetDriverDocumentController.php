<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\DriverDocument\DriverDocumentData;
use App\Http\Controllers\Controller;
use App\Models\DriverDocument;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Document', 'Get details of a specific document')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Document retrieved successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Document not found')]
final class GetDriverDocumentController extends Controller
{
    public function __invoke(
        #[CurrentUser] User $user,
        DriverDocument $document,
    ): JsonResponse {
        $this->authorize('view', $document);

        return ApiResponse::success(
            data: DriverDocumentData::fromModel($document),
        );
    }
}
