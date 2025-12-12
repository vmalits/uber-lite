<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\OtpCode;
use Illuminate\Database\DatabaseManager;
use Random\RandomException;
use Throwable;

final class CreateOtpCode
{
    private const int EXPIRATION_MINUTES = 5;

    public function __construct(private readonly DatabaseManager $databaseManager) {}

    /**
     * @throws RandomException|Throwable
     */
    public function handle(string $phone): OtpCode
    {
        return $this->databaseManager->transaction(
            callback: function () use ($phone): OtpCode {
                OtpCode::where('phone', $phone)->update(['used' => true]);

                $code = random_int(100000, 999999);

                return OtpCode::query()->create([
                    'phone'      => $phone,
                    'code'       => $code,
                    'expires_at' => now()->addMinutes(self::EXPIRATION_MINUTES),
                ]);
            }, attempts: 3);
    }
}
