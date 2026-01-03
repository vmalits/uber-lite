<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\artisan;

it('creates an admin successfully', function () {
    $phone = '+37369123123';
    $email = 'admin'.Str::random(5).'@test.com';
    $password = 'securePassword123';

    artisan('admin:create')
        ->expectsQuestion('Phone number (+373...)', $phone)
        ->expectsQuestion('Email (optional)', $email)
        ->expectsQuestion('Password', $password)
        ->expectsQuestion('Confirm password', $password)
        ->expectsOutput('Admin created successfully!')
        ->expectsOutputToContain("Phone: {$phone}")
        ->expectsOutputToContain('Role:  admin')
        ->assertSuccessful();

    $this->assertDatabaseHas('users', [
        'phone' => $phone,
        'email' => $email,
        'role'  => 'admin',
    ]);
});

it('fails with duplicate phone', function () {
    $phone = '+37360000124';
    $email = 'admin'.Str::random(5).'@test.com';
    $password = 'securePassword123';

    User::factory()->create([
        'phone' => $phone,
        'role'  => 'admin',
    ]);

    artisan('admin:create')
        ->expectsQuestion('Phone number (+373...)', $phone)
        ->expectsQuestion('Email (optional)', $email)
        ->expectsQuestion('Password', $password)
        ->expectsQuestion('Confirm password', $password)
        ->expectsOutput('The phone has already been taken.')
        ->assertFailed();
});

it('fails with invalid email', function () {
    $phone = '+37360000125';
    $email = 'not-an-email';
    $password = 'securePassword123';

    artisan('admin:create')
        ->expectsQuestion('Phone number (+373...)', $phone)
        ->expectsQuestion('Email (optional)', $email)
        ->expectsQuestion('Password', $password)
        ->expectsQuestion('Confirm password', $password)
        ->expectsOutputToContain('email')
        ->assertFailed();
});

it('fails with short password', function () {
    $phone = '+37360000126';
    $email = 'admin'.Str::random(5).'@test.com';
    $password = 'short12';

    $exitCode = artisan('admin:create')
        ->expectsQuestion('Phone number (+373...)', $phone)
        ->expectsQuestion('Email (optional)', $email)
        ->expectsQuestion('Password', $password)
        ->expectsQuestion('Confirm password', $password)
        ->run();

    $this->assertNotEquals(0, $exitCode, 'Command should fail with short password');
});

it('fails when passwords do not match', function () {
    $phone = '+37360000127';
    $email = 'admin'.Str::random(5).'@test.com';
    $password = 'securePassword123';
    $wrongConfirm = 'different123';

    artisan('admin:create')
        ->expectsQuestion('Phone number (+373...)', $phone)
        ->expectsQuestion('Email (optional)', $email)
        ->expectsQuestion('Password', $password)
        ->expectsQuestion('Confirm password', $wrongConfirm)
        ->expectsOutput('Passwords do not match.')
        ->assertFailed();
});
