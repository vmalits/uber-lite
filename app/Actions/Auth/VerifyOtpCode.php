<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\OtpCode;
use App\Queries\Auth\FindValidOtpCodeQuery;

final readonly class VerifyOtpCode
{
    public function __construct(
        private FindValidOtpCodeQuery $findValidOtpCodeQuery,
    ) {}

    public function handle(string $phone, string $code): ?OtpCode
    {
        return $this->findValidOtpCodeQuery->execute($phone, $code);
    }
}
