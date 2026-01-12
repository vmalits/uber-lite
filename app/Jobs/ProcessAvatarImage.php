<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Services\Avatar\Storage\AvatarStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class ProcessAvatarImage
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param array<string, string> $tempFiles ['sm' => '/tmp/...', 'md' => '/tmp/...', 'lg' => '/tmp/...']
     */
    public function __construct(
        public readonly User $user,
        public readonly array $tempFiles,
    ) {}

    public function handle(): void
    {
        Log::info(
            'ProcessAvatarImage job started',
            ['user_id' => $this->user->id, 'temp_files_count' => \count($this->tempFiles)],
        );

        /** @var string $disk */
        $disk = config('filesystems.default', 'public');
        Log::info('Using disk', ['disk' => $disk]);

        $storage = new AvatarStorageService(disk: $disk);

        try {
            $storage->deleteOldAvatar($this->user->avatar_paths ?? []);

            $basePath = "avatars/{$this->user->id}";

            $paths = $storage->moveTempFilesToStorage($this->tempFiles, $basePath);

            Log::info('Avatar files uploaded', ['user_id' => $this->user->id, 'paths' => $paths]);

            $this->user->update(['avatar_paths' => $paths]);
        } finally {
            $this->cleanupTempFiles();
        }
    }

    public function failed(\Throwable $e): void
    {
        $this->user->update(['avatar_paths' => null]);
        $this->cleanupTempFiles();
    }

    private function cleanupTempFiles(): void
    {
        foreach ($this->tempFiles as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
