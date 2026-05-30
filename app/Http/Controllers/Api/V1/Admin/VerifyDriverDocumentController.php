<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\DriverDocument\VerifyDriverDocumentAction;
use App\Data\DriverDocument\DriverDocumentData;
use App\Enums\DriverDocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DriverDocument\VerifyDriverDocumentRequest;
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

#[Group('Admin')]
#[Endpoint('Verify Document', 'Approve or reject a driver document')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Document verified successfully')]
#[Response(status: 403, description: 'Unauthorized – not an admin')]
#[Response(status: 404, description: 'Document not found')]
#[Response(status: 422, description: 'Validation errors')]
final class VerifyDriverDocumentController extends Controller
{
    public function __construct(
        private readonly VerifyDriverDocumentAction $verifyDocument,
    ) {}

    public function __invoke(
        DriverDocument $document,
        VerifyDriverDocumentRequest $request,
        #[CurrentUser] User $admin,
    ): JsonResponse {
        $data = new \App\Data\DriverDocument\VerifyDriverDocumentData(
            status: DriverDocumentStatus::from($request->string('status')->toString()),
            rejection_reason: $request->filled('rejection_reason')
                ? $request->string('rejection_reason')->toString()
                : null,
        );

        $document = $this->verifyDocument->handle($document, $data, $admin);

        return ApiResponse::success(
            data: DriverDocumentData::fromModel($document),
            message: __('messages.document.verified'),
        );
    }
}
