<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use App\Data\Driver\UpdateLocationData;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @bodyParam lat float required lat. Example: 55.751244
     * @bodyParam lng float required lng. Example: 37.618423
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'lat' => [
                'description' => 'Latitude',
                'example'     => 55.751244,
                'type'        => 'float',
                'required'    => true,
            ],
            'lng' => [
                'description' => 'Longitude',
                'example'     => 37.618423,
                'type'        => 'float',
                'required'    => true,
            ],
        ];
    }

    public function toDto(): UpdateLocationData
    {
        return new UpdateLocationData(
            lat: $this->float('lat'),
            lng: $this->float('lng'),
        );
    }
}
