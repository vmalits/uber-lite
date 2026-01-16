<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\Auth\ActiveOtpCodeAlreadyExistsException;
use App\Models\OtpCode;
use App\Queries\Auth\FindValidOtpCodeByPhoneQueryInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Database\DatabaseManager;
use Random\RandomException;
use Throwable;

final readonly class CreateOtpCodeAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private FindValidOtpCodeByPhoneQueryInterface $findValidOtpCodeByPhoneQuery,
        private ConfigRepository $config,
    ) {}

    /**
     * @throws RandomException|Throwable
     */
    public function handle(string $phone, string $code): OtpCode
    {
        return $this->databaseManager->transaction(
            callback: function () use ($phone, $code): OtpCode {
                $existingCode = $this->findValidOtpCodeByPhoneQuery->execute(phone: $phone, lock: true);

                if ($existingCode !== null) {
                    throw new ActiveOtpCodeAlreadyExistsException('An active OTP code already exists.');
                }

                OtpCode::where('phone', $phone)->update(['used' => true]);

                $rawExpiration = $this->config->get('otp.expiration_minutes', 5);
                $expirationMinutes = is_numeric($rawExpiration) ? (int) $rawExpiration : 5;

                return OtpCode::query()->create([
                    'phone'      => $phone,
                    'code'       => $code,
                    'expires_at' => now()->addMinutes($expirationMinutes),
                ]);
            },
            attempts: 3);
    }
}
