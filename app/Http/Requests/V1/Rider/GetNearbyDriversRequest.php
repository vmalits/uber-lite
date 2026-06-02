<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\NearbyDriversRequestData;
use App\Enums\VehicleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class GetNearbyDriversRequest extends FormRequest
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
            'lat'          => ['required', 'numeric', 'between:-90,90'],
            'lng'          => ['required', 'numeric', 'between:-180,180'],
            'radius'       => ['sometimes', 'integer', 'min:100', 'max:10000'],
            'vehicle_type' => ['sometimes', 'string', new Enum(VehicleType::class)],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'lat' => [
                'description' => 'Search center latitude',
                'example'     => 47.0105,
                'type'        => 'number',
            ],
            'lng' => [
                'description' => 'Search center longitude',
                'example'     => 28.8638,
                'type'        => 'number',
            ],
            'radius' => [
                'description' => 'Search radius in meters (default: 3000, max: 10000)',
                'example'     => 5000,
                'type'        => 'integer',
            ],
            'vehicle_type' => [
                'description' => 'Filter by vehicle type',
                'example'     => 'sedan',
                'type'        => 'string',
            ],
        ];
    }

    public function toData(): NearbyDriversRequestData
    {
        $validated = $this->validated();

        $lat = is_numeric($validated['lat']) ? (float) $validated['lat'] : 0.0;
        $lng = is_numeric($validated['lng']) ? (float) $validated['lng'] : 0.0;
        $radius = isset($validated['radius']) && is_numeric($validated['radius'])
            ? (int) $validated['radius']
            : NearbyDriversRequestData::DEFAULT_RADIUS_METERS;
        $vehicleType = \is_string($validated['vehicle_type'] ?? null)
            ? VehicleType::tryFrom($validated['vehicle_type'])
            : null;

        return new NearbyDriversRequestData(
            lat: $lat,
            lng: $lng,
            radiusMeters: $radius,
            vehicleType: $vehicleType,
        );
    }
}
