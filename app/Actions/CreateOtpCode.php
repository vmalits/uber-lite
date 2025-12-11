<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\OtpCode;
use Random\RandomException;

class CreateOtpCode
{
    private const int EXPIRATION_MINUTES = 5;

    /**
     * @throws RandomException
     */
    public function handle(string $phone): OtpCode
    {
        $code = random_int(100000, 999999);

        return OtpCode::query()->create([
            'phone'      => $phone,
            'code'       => $code,
            'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
        ]);
    }
}
