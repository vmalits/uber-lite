<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Safety;

use App\Data\Safety\SosData;
use Illuminate\Foundation\Http\FormRequest;

final class SendSosRequest extends FormRequest
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
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'message'   => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, array<string, string|int|float>>
     */
    public function bodyParameters(): array
    {
        return [
            'latitude' => [
                'description' => 'The current latitude of the user',
                'example'     => 47.0105,
            ],
            'longitude' => [
                'description' => 'The current longitude of the user',
                'example'     => 28.8638,
            ],
            'message' => [
                'description' => 'Optional emergency message',
                'example'     => 'I need help immediately!',
            ],
        ];
    }

    public function toData(): SosData
    {
        return SosData::from($this->validated());
    }
}
