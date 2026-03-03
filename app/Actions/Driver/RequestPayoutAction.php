<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\RequestPayoutData;
use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class RequestPayoutAction
{
    public function handle(User $driver, RequestPayoutData $data): DriverPayout
    {
        if ($driver->driver_balance < $data->amount) {
            throw ValidationException::withMessages([
                'amount' => ['Insufficient balance.'],
            ]);
        }

        return DB::transaction(function () use ($driver, $data): DriverPayout {
            $driver->decrement('driver_balance', $data->amount);

            return DriverPayout::query()->create([
                'driver_id'             => $driver->id,
                'amount'                => $data->amount,
                'status'                => PayoutStatus::PENDING,
                'method'                => $data->method,
                'bank_name'             => $data->bankName,
                'bank_account_number'   => $data->bankAccountNumber,
                'bank_routing_number'   => $data->bankRoutingNumber,
                'crypto_wallet_address' => $data->cryptoWalletAddress,
                'crypto_currency'       => $data->cryptoCurrency,
                'requested_at'          => now(),
                'description'           => $data->description,
            ]);
        });
    }
}
