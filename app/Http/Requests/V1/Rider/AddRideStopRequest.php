<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\AddRideStopData;
use Illuminate\Foundation\Http\FormRequest;

class AddRideStopRequest extends FormRequest
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
            'address' => ['required', 'string', 'max:500'],
            'lat'     => ['nullable', 'numeric', 'between:-90,90'],
            'lng'     => ['nullable', 'numeric', 'between:-180,180', 'required_with:lat'],
        ];
    }

    /**
     * @return array<string, array<string, float|int|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'address' => [
                'description' => 'Stop address.',
                'example'     => '123 Main St, City',
            ],
            'lat' => [
                'description' => 'Stop latitude.',
                'example'     => 40.7128,
            ],
            'lng' => [
                'description' => 'Stop longitude.',
                'example'     => -74.0060,
            ],
        ];
    }

    public function toAddRideStopData(): AddRideStopData
    {
        return new AddRideStopData(
            address: $this->string('address')->toString(),
            lat: $this->float('lat'),
            lng: $this->float('lng'),
        );
    }
}
