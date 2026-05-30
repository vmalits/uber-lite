<?php

declare(strict_types=1);

namespace App\Actions\DriverDocument;

use App\Models\DriverDocument;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteDriverDocumentAction
{
    public function handle(DriverDocument $document): bool
    {
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        return (bool) $document->delete();
    }
}
