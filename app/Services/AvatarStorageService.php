<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\User\UploadFailedException;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class AvatarStorageService
{
    private const string DISK = 'public';

    public function store(User $user, UploadedFile $file): string
    {
        $extension = $file->extension();

        $path = \sprintf(
            'avatars/%s/avatar.%s',
            $user->id,
            $extension,
        );

        if (\array_key_exists('avatar_path', $user->getAttributes()) && $user->avatar_path) {
            Storage::disk(self::DISK)->delete($user->avatar_path);
        }

        $stored = $file->storeAs(
            path: \dirname($path),
            name: basename($path),
            options: ['disk' => self::DISK],
        );

        if ($stored === false) {
            throw UploadFailedException::create();
        }

        return $stored;
    }

    public function url(string $path): string
    {
        return Storage::disk(self::DISK)->url($path);
    }
}
