<?php

declare(strict_types=1);

namespace App\Queries\Auth;

use App\Models\OtpCode;
use Illuminate\Support\Carbon;

final class FindValidOtpCodeByPhoneQuery implements FindValidOtpCodeByPhoneQueryInterface
{
    public function execute(string $phone, bool $lock = false): ?OtpCode
    {
        $query = OtpCode::query()
            ->where('phone', $phone)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->orderByDesc('id');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }
}
