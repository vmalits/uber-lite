<?php

declare(strict_types=1);

namespace App\Services;

class OtpService
{
    public function generateOtpCode(): string
    {
        return \sprintf('%06d', random_int(0, 999999));
    }
}
