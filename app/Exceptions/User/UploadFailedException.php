<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use RuntimeException;

final class UploadFailedException extends RuntimeException
{
    public static function create(string $message = 'Failed to upload avatar.'): self
    {
        return new self($message);
    }
}
