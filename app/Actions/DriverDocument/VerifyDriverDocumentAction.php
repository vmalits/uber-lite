<?php

declare(strict_types=1);

namespace App\Actions\DriverDocument;

use App\Data\DriverDocument\VerifyDriverDocumentData;
use App\Enums\DriverDocumentStatus;
use App\Models\DriverDocument;
use App\Models\User;

final readonly class VerifyDriverDocumentAction
{
    public function handle(DriverDocument $document, VerifyDriverDocumentData $data, User $admin): DriverDocument
    {
        $document->update([
            'status'           => $data->status,
            'verified_by'      => $admin->id,
            'verified_at'      => now(),
            'rejection_reason' => $data->status === DriverDocumentStatus::REJECTED
                ? $data->rejection_reason
                : null,
        ]);

        $document->refresh();

        return $document;
    }
}
