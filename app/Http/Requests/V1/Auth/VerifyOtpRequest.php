<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\Data\Auth\VerifyOtpData;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'phone:MD'],
            'code'  => ['required', 'string', 'size:6'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'phone' => [
                'description' => 'User\'s phone number (Moldova). Include country code, e.g., +37361234567.',
                'example'     => '+37361234567',
            ],
            'code' => [
                'description' => '6-digit one-time password (OTP) sent to the provided phone number.',
                'example'     => '123456',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): VerifyOtpData
    {
        return new VerifyOtpData(
            phone: $this->string('phone')->toString(),
            code: $this->string('code')->toString(),
        );
    }
}
