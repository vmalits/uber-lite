<?php

declare(strict_types=1);

namespace App\Queries\Auth;

use App\Models\OtpCode;

interface FindValidOtpCodeByPhoneQueryInterface
{
    public function execute(string $phone): ?OtpCode;
}
