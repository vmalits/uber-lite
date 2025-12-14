<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\OtpCode;
use App\Models\User;
use App\Queries\Auth\FindValidOtpCodeByPhoneQuery;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Hash;
use Throwable;

final readonly class VerifyOtpCode
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private FindValidOtpCodeByPhoneQuery $findValidOtp,
    ) {}

    /**
     *
     * @throws Throwable
     */
    public function handle(string $phone, string $code): bool
    {
        /** @var OtpCode|null $otp */
        $otp = $this->findValidOtp->execute($phone);

        if ($otp === null || ! Hash::check($code, $otp->code)) {
            return false;
        }

        $this->databaseManager->transaction(function () use ($otp, $phone): void {
            $otp->update(['used' => true]);

            /** @var User $user */
            $user = User::query()->firstOrCreate(
                ['phone' => $phone],
                ['profile_step' => ProfileStep::PHONE_VERIFIED->value],
            );

            $user->forceFill([
                'phone_verified_at' => now(),
                'last_login_at'     => now(),
                'profile_step'      => ProfileStep::PHONE_VERIFIED,
            ])->save();
        }, 3);

        return true;
    }
}
