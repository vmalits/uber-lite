<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\DriverDocument\UploadDriverDocumentAction;
use App\Data\DriverDocument\DriverDocumentData;
use App\Enums\DriverDocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DriverDocument\StoreDriverDocumentRequest;
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
#[Endpoint('Upload Document', 'Upload a verification document')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Document uploaded successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 422, description: 'Validation errors')]
final class StoreDriverDocumentController extends Controller
{
    public function __construct(
        private readonly UploadDriverDocumentAction $uploadDocument,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        StoreDriverDocumentRequest $request,
    ): JsonResponse {
        $this->authorize('create', DriverDocument::class);

        $document = $this->uploadDocument->handle(
            driver: $user,
            type: DriverDocumentType::from($request->string('type')->toString()),
            file: $request->file('document'),
        );

        return ApiResponse::created(
            data: DriverDocumentData::fromModel($document),
            message: __('messages.document.uploaded'),
        );
    }
}
