<?php

declare(strict_types=1);

use App\Models\OtpCode;
use Illuminate\Support\Carbon;

uses(Tests\TestCase::class);

it('casts expires_at to Carbon and used to boolean with default false', function (): void {
    $otp = OtpCode::query()->create([
        'phone'      => '+37360000009',
        'code'       => '999999',
        // to provide a string, casting should convert to Carbon
        'expires_at' => now()->addMinute()->toDateTimeString(),
        // omit "used" to rely on default false in migration
    ]);

    // refresh from DB so the default value is applied
    $otp->refresh();

    expect($otp->expires_at)->toBeInstanceOf(Carbon::class)
        ->and($otp->used)->toBeFalse();
});

it('prunable() selects expired or used, and ignores valid codes', function (): void {
    $expired = OtpCode::query()->create([
        'phone'      => '+37360000001',
        'code'       => '111111',
        'expires_at' => now()->subMinute(),
        'used'       => false,
    ]);

    $used = OtpCode::query()->create([
        'phone'      => '+37360000002',
        'code'       => '222222',
        'expires_at' => now()->addMinutes(5),
        'used'       => true,
    ]);

    $valid = OtpCode::query()->create([
        'phone'      => '+37360000003',
        'code'       => '333333',
        'expires_at' => now()->addMinutes(5),
        'used'       => false,
    ]);

    $ids = new OtpCode()->prunable()->pluck('id')->all();

    expect($ids)->toContain($expired->id)
        ->and($ids)->toContain($used->id)
        ->and($ids)->not->toContain($valid->id);
});
