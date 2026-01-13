<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SetLocaleRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'locale' => [
                'required',
                'string',
                Rule::in(Locale::values()),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'locale' => [
                'description' => 'User preferred locale. Supported values: ro (Romanian), ru (Russian), en (English).',
                'type'        => 'string',
                'required'    => true,
                'example'     => 'ro',
            ],
        ];
    }
}
