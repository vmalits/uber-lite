<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class ApplyReferralCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'referral_code' => ['required', 'string', 'max:12'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'referral_code' => [
                'description' => 'The referral code from another user.',
                'example'     => 'ABC12345',
            ],
        ];
    }

    public function getReferralCode(): string
    {
        return strtoupper(trim($this->string('referral_code')->toString()));
    }
}
