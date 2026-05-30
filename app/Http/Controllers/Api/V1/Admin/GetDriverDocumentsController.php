<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\DriverDocument\DriverDocumentData;
use App\Http\Controllers\Controller;
use App\Models\DriverDocument;
use App\Models\User;
use App\Queries\DriverDocument\GetDriverDocumentsForAdminQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Driver Documents', 'List all documents for a specific driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'List of driver documents')]
#[Response(status: 403, description: 'Unauthorized – not an admin')]
#[Response(status: 404, description: 'Driver not found')]
final class GetDriverDocumentsController extends Controller
{
    public function __construct(
        private readonly GetDriverDocumentsForAdminQueryInterface $getDocumentsQuery,
    ) {}

    public function __invoke(User $driver): JsonResponse
    {
        $documents = $this->getDocumentsQuery->execute($driver);

        $documents->through(
            fn (DriverDocument $document) => DriverDocumentData::fromModel($document),
        );

        return ApiResponse::success($documents);
    }
}
