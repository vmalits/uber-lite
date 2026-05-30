<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\DriverDocument\DriverDocumentData;
use App\Http\Controllers\Controller;
use App\Models\DriverDocument;
use App\Models\User;
use App\Queries\DriverDocument\GetDriverDocumentsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Documents', 'Get all documents uploaded by the driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'List of driver documents')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetDriverDocumentsController extends Controller
{
    public function __construct(
        private readonly GetDriverDocumentsQueryInterface $getDocumentsQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $this->authorize('viewAny', DriverDocument::class);

        $documents = $this->getDocumentsQuery->execute($user);

        /** @var array<string, mixed> $data */
        $data = DriverDocumentData::collect($documents)->toArray();

        return ApiResponse::success(
            data: $data,
        );
    }
}
