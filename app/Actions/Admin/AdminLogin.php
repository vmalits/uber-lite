<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\AdminLoginData;
use App\Data\Admin\AdminLoginResponseData;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final readonly class AdminLogin
{
    public function handle(AdminLoginData $dto): AdminLoginResponseData
    {
        $user = User::where('phone', $dto->phone)
            ->where('role', UserRole::ADMIN)
            ->first();

        if (
            ! $user ||
            $user->password === null ||
            ! Hash::check($dto->password, $user->password)
        ) {
            throw new UnauthorizedHttpException('api', 'Invalid credentials');
        }

        $token = $user->createToken('admin')->plainTextToken;

        return new AdminLoginResponseData(token: $token);
    }
}
