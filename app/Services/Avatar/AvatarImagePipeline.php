<?php

declare(strict_types=1);

namespace App\Services\Avatar;

use App\Enums\AvatarSize;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Random\RandomException;
use Spatie\ImageOptimizer\OptimizerChain;

final readonly class AvatarImagePipeline
{
    private const int WEBP_QUALITY = 85;

    public function __construct(
        private ImageManager $manager,
        private OptimizerChain $optimizer,
    ) {}

    /**
     * @throws RandomException
     *
     * @return array<string, string> paths to temp files
     */
    public function process(string $sourceFilePath): array
    {
        $tempFiles = [];

        foreach (AvatarSize::cases() as $size) {
            $tempFile = $this->processSize($sourceFilePath, $size->pixels());
            $tempFiles[$size->value] = $tempFile;
        }

        return $tempFiles;
    }

    /**
     * @throws RandomException
     */
    private function processSize(string $sourceFilePath, int $pixels): string
    {
        $image = $this->manager->read($sourceFilePath)->resize($pixels, $pixels);

        $tempFile = $this->saveTempFile($image);

        $this->optimizer->optimize($tempFile);

        return $tempFile;
    }

    /**
     * @throws RandomException
     */
    private function saveTempFile(ImageInterface $image): string
    {
        $tempDir = sys_get_temp_dir();
        $tempFile = $tempDir.'/avatar_'.bin2hex(random_bytes(16)).'.webp';

        $image->toWebp(self::WEBP_QUALITY)->save($tempFile);

        return $tempFile;
    }

    /**
     * @param array<string, string> $tempFilePaths
     */
    public function cleanupTempFiles(array $tempFilePaths): void
    {
        foreach ($tempFilePaths as $path) {
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }
}
