<?php

declare(strict_types=1);

namespace App\Data\User;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

final class UploadAvatarData extends Data
{
    public function __construct(
        public readonly UploadedFile $avatar,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            avatar: $request->file('avatar'),
        );
    }
}
