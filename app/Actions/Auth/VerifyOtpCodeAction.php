<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Models\OtpCode;
use App\Models\User;
use App\Queries\Auth\FindValidOtpCodeByPhoneQueryInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Hash;
use Throwable;

final readonly class VerifyOtpCodeAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private FindValidOtpCodeByPhoneQueryInterface $findValidOtp,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(string $phone, string $code): ?User
    {
        return $this->databaseManager->transaction(
            callback: function () use ($phone, $code): ?User {
                /** @var OtpCode|null $otp */
                $otp = $this->findValidOtp->execute($phone, lock: true);

                if ($otp === null || ! Hash::check($code, $otp->code)) {
                    return null;
                }

                $otp->update(['used' => true]);

                /** @var User $user */
                $user = User::query()->firstOrCreate(['phone' => $phone]);

                $user->forceFill([
                    'phone_verified_at' => now(),
                    'last_login_at'     => now(),
                ]);

                if ($user->profile_step === null) {
                    $user->profile_step = ProfileStep::PHONE_VERIFIED;
                }

                $user->save();

                return $user;
            },
            attempts: 3,
        );
    }
}
