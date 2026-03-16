<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\UpdateFavoriteLocationData;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateFavoriteLocationRequest extends FormRequest
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
            'name'    => ['sometimes', 'string', 'max:50'],
            'lat'     => ['sometimes', 'numeric', 'between:-90,90'],
            'lng'     => ['sometimes', 'numeric', 'between:-180,180'],
            'address' => ['sometimes', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, array<string, string|int|float>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'The name of the favorite location',
                'example'     => 'Home',
            ],
            'lat' => [
                'description' => 'The latitude of the location',
                'example'     => 47.010,
            ],
            'lng' => [
                'description' => 'The longitude of the location',
                'example'     => 28.863,
            ],
            'address' => [
                'description' => 'The address of the favorite location',
                'example'     => 'Strada Stefan cel Mare 123, Chisinau',
            ],
        ];
    }

    public function toData(): UpdateFavoriteLocationData
    {
        return UpdateFavoriteLocationData::from($this->validated());
    }
}
