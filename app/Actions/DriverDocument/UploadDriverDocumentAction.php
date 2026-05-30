<?php

declare(strict_types=1);

namespace App\Actions\DriverDocument;

use App\Enums\DriverDocumentStatus;
use App\Enums\DriverDocumentType;
use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class UploadDriverDocumentAction
{
    public function handle(User $driver, DriverDocumentType $type, UploadedFile $file): DriverDocument
    {
        $path = Storage::putFile('documents/'.$driver->id, $file);

        return DriverDocument::query()->create([
            'driver_id'     => $driver->id,
            'type'          => $type,
            'file_path'     => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'status'        => DriverDocumentStatus::PENDING,
        ]);
    }
}
