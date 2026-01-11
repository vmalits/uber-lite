<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Random\RandomException;

readonly class OtpService
{
    public function __construct(
        private ConfigRepository $config,
    ) {}

    /**
     * @throws RandomException
     */
    public function generateOtpCode(): string
    {
        $rawLength = $this->config->get('otp.code_length', 6);
        $codeLength = is_numeric($rawLength) ? (int) $rawLength : 6;
        $max = (int) str_repeat('9', $codeLength);

        return \sprintf('%0'.$codeLength.'d', random_int(0, $max));
    }
}
