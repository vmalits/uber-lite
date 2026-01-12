<?php

declare(strict_types=1);

namespace App\Services\Avatar\Storage;

use App\Enums\AvatarSize;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final readonly class AvatarStorageService
{
    public function __construct(
        private string $disk,
    ) {}

    /**
     * @return array<string, string>
     */
    public function upload(UploadedFile $file, string $basePath): array
    {
        $tempPath = $this->storeTempFile($file);

        try {
            $paths = [];

            foreach (AvatarSize::cases() as $size) {
                $path = "{$basePath}/{$size->path()}";
                $contents = File::get($tempPath);
                Storage::disk($this->disk)->put($path, $contents);
                $paths[$size->value] = $path;
            }

            return $paths;
        } finally {
            Storage::disk('local')->delete($tempPath);
        }
    }

    /**
     * @param array<string, string> $tempFiles ['sm' => '/tmp/...', 'md' => '/tmp/...', ...]
     *
     * @throws FileNotFoundException
     *
     * @return array<string, string>
     */
    public function moveTempFilesToStorage(array $tempFiles, string $basePath): array
    {
        $paths = [];

        foreach (AvatarSize::cases() as $size) {
            $tempFile = $tempFiles[$size->value] ?? null;
            if ($tempFile === null) {
                continue;
            }

            $path = "{$basePath}/{$size->path()}";
            $contents = File::get($tempFile);
            Storage::disk($this->disk)->put($path, $contents);
            $paths[$size->value] = $path;
        }

        return $paths;
    }

    public function delete(string $basePath): void
    {
        foreach (AvatarSize::cases() as $size) {
            $path = "{$basePath}/{$size->path()}";
            Storage::disk($this->disk)->delete($path);
        }
    }

    /**
     * @param array<string> $oldPaths
     */
    public function deleteOldAvatar(array $oldPaths): void
    {
        foreach ($oldPaths as $path) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    public function generateBasePath(int|string $userId): string
    {
        return 'avatars/'.$userId;
    }

    private function storeTempFile(UploadedFile $file): string
    {
        $tempPath = 'temp/'.Str::random(40);
        Storage::disk('local')->put($tempPath, $file->getContent());

        return $tempPath;
    }
}
