<?php

declare(strict_types=1);

namespace App\Data\DriverDocument;

use App\Enums\DriverDocumentStatus;
use Spatie\LaravelData\Data;

final class VerifyDriverDocumentData extends Data
{
    public function __construct(
        public DriverDocumentStatus $status,
        public ?string $rejection_reason = null,
    ) {}
}
