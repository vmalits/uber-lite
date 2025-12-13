<?php

declare(strict_types=1);

use App\Models\OtpCode;

it('prunes expired and used OTP codes while keeping valid ones', function (): void {
    // expired & unused
    $expired = OtpCode::query()->create([
        'phone'      => '+37360000001',
        'code'       => '111111',
        'expires_at' => now()->subMinute(),
        'used'       => false,
    ]);

    // not expired but used
    $used = OtpCode::query()->create([
        'phone'      => '+37360000002',
        'code'       => '222222',
        'expires_at' => now()->addMinutes(10),
        'used'       => true,
    ]);

    // valid (not expired & not used)
    $valid = OtpCode::query()->create([
        'phone'      => '+37360000003',
        'code'       => '333333',
        'expires_at' => now()->addMinutes(10),
        'used'       => false,
    ]);

    // act: run Laravel's model prune command for OtpCode
    $this->artisan('model:prune', ['--model' => [OtpCode::class]])->assertExitCode(0);

    // assert
    expect(OtpCode::query()->find($expired->id))->toBeNull()
        ->and(OtpCode::query()->find($used->id))->toBeNull()
        ->and(OtpCode::query()->find($valid->id))->not->toBeNull();
});
