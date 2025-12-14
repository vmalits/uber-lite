<?php

declare(strict_types=1);

namespace App\Services;

use Random\RandomException;

class OtpService
{
    /**
     * @throws RandomException
     */
    public function generateOtpCode(): string
    {
        return \sprintf('%06d', random_int(0, 999999));
    }
}
