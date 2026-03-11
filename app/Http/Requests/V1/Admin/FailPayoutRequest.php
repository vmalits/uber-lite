<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class FailPayoutRequest extends FormRequest
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
            'reason' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'reason' => [
                'description' => 'Reason for payout failure',
                'example'     => 'Bank rejected the transaction',
            ],
        ];
    }

    public function reason(): string
    {
        return $this->string('reason')->toString();
    }
}
