<?php

declare(strict_types=1);

namespace App\Data\DriverDocument;

use App\Data\DateData;
use App\Enums\DriverDocumentStatus;
use App\Enums\DriverDocumentType;
use App\Models\DriverDocument;
use Spatie\LaravelData\Data;

final class DriverDocumentData extends Data
{
    public function __construct(
        public string $id,
        public DriverDocumentType $type,
        public string $file_path,
        public string $original_name,
        public string $mime_type,
        public int $size,
        public DriverDocumentStatus $status,
        public ?string $rejection_reason,
        public ?string $verified_by,
        public ?DateData $verified_at,
        public ?DateData $expires_at,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(DriverDocument $document): self
    {
        /** @var DriverDocumentType $type */
        $type = $document->type;

        /** @var DriverDocumentStatus $status */
        $status = $document->status;

        return new self(
            id: $document->id,
            type: $type,
            file_path: $document->file_path,
            original_name: $document->original_name,
            mime_type: $document->mime_type,
            size: $document->size,
            status: $status,
            rejection_reason: $document->rejection_reason,
            verified_by: $document->verified_by,
            verified_at: $document->verified_at ? DateData::fromCarbon($document->verified_at) : null,
            expires_at: $document->expires_at ? DateData::fromCarbon($document->expires_at) : null,
            created_at: DateData::fromCarbon($document->created_at),
            updated_at: DateData::fromCarbon($document->updated_at),
        );
    }
}
