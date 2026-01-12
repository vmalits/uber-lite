<?php

declare(strict_types=1);

namespace App\Services\Avatar;

use App\Enums\AvatarSize;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Random\RandomException;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory;

final class AvatarImagePipeline
{
    private const int WEBP_QUALITY = 85;

    /**
     * @throws RandomException
     *
     * @return array<string, string> paths to temp files
     */
    public function process(string $sourceFilePath): array
    {
        $optimizer = OptimizerChainFactory::create();

        $tempFiles = [];

        foreach (AvatarSize::cases() as $size) {
            $tempFile = $this->processSize($sourceFilePath, $size->pixels(), $optimizer);
            $tempFiles[$size->value] = $tempFile;
        }

        return $tempFiles;
    }

    /**
     * @throws RandomException
     */
    private function processSize(string $sourceFilePath, int $pixels, OptimizerChain $optimizer): string
    {
        $manager = new ImageManager(new Driver);

        $image = $manager->read($sourceFilePath)->resize($pixels, $pixels);

        $tempFile = $this->saveTempFile($image);

        $optimizer->optimize($tempFile);

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
