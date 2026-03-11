<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class AddTipRequest extends FormRequest
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
            'amount'  => ['required', 'integer', 'min:1', 'max:100000'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'amount' => [
                'description' => 'Tip amount in cents.',
                'example'     => 500,
            ],
            'comment' => [
                'description' => 'Optional tip comment.',
                'example'     => 'Thank you!',
            ],
        ];
    }
}
