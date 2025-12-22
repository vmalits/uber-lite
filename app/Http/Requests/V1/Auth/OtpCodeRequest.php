<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Auth;

use App\Data\Auth\OtpCodeData;
use Illuminate\Foundation\Http\FormRequest;

class OtpCodeRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'phone:MD'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.phone'    => 'Phone number is invalid.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'phone' => [
                'description' => 'Phone number of the user to receive the OTP. Must be a valid Moldova phone number',
                'example'     => '+37360123456',
                'required'    => true,
            ],
        ];
    }

    public function toDto(): OtpCodeData
    {
        return new OtpCodeData(
            phone: $this->string('phone')->toString(),
        );
    }
}
