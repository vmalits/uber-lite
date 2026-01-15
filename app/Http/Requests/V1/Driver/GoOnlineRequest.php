<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use Illuminate\Foundation\Http\FormRequest;

final class GoOnlineRequest extends FormRequest
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
        ];
    }

    /**
     * @return array<string, array<string, float|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'latitude' => [
                'description' => 'The latitude of the driver\'s current location.',
                'example'     => 47.0105,
            ],
            'longitude' => [
                'description' => 'The longitude of the driver\'s current location.',
                'example'     => 28.8638,
            ],
        ];
    }
}
