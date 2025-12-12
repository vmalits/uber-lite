<?php

declare(strict_types=1);

namespace App\Queries\Auth;

use App\Models\OtpCode;
use Illuminate\Support\Carbon;

final class FindValidOtpCodeQuery
{
    public function execute(string $phone, string $code): ?OtpCode
    {
        return OtpCode::query()
            ->where('phone', $phone)
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->orderByDesc('id')
            ->first();
    }
}
