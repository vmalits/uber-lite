<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\DriverDocument\DeleteDriverDocumentAction;
use App\Http\Controllers\Controller;
use App\Models\DriverDocument;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Delete Document', 'Delete an uploaded document')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Document deleted successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Document not found')]
final class DeleteDriverDocumentController extends Controller
{
    public function __construct(
        private readonly DeleteDriverDocumentAction $deleteDocument,
    ) {}

    public function __invoke(DriverDocument $document): JsonResponse
    {
        $this->authorize('delete', $document);

        $this->deleteDocument->handle($document);

        return ApiResponse::success(
            message: __('messages.success.deleted'),
        );
    }
}
