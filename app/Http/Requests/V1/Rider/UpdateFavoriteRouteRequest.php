<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use App\Data\Rider\FavoriteRouteData;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateFavoriteRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name'                => ['sometimes', 'required', 'string', 'max:255'],
            'origin_address'      => ['sometimes', 'required', 'string', 'max:500'],
            'origin_lat'          => ['sometimes', 'required', 'numeric', 'between:-90,90'],
            'origin_lng'          => ['sometimes', 'required', 'numeric', 'between:-180,180'],
            'destination_address' => ['sometimes', 'required', 'string', 'max:500'],
            'destination_lat'     => ['sometimes', 'required', 'numeric', 'between:-90,90'],
            'destination_lng'     => ['sometimes', 'required', 'numeric', 'between:-180,180'],
            'type'                => ['nullable', 'string', 'in:home,work,school,gym,other'],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Route name (e.g., Home-Work)',
                'example'     => 'Home to Work',
            ],
            'origin_address' => [
                'description' => 'Origin address',
                'example'     => '123 Main St, New York',
            ],
            'origin_lat' => [
                'description' => 'Origin latitude',
                'example'     => '40.7128',
            ],
            'origin_lng' => [
                'description' => 'Origin longitude',
                'example'     => '-74.0060',
            ],
            'destination_address' => [
                'description' => 'Destination address',
                'example'     => '456 Park Ave, New York',
            ],
            'destination_lat' => [
                'description' => 'Destination latitude',
                'example'     => '40.7589',
            ],
            'destination_lng' => [
                'description' => 'Destination longitude',
                'example'     => '-73.9851',
            ],
            'type' => [
                'description' => 'Route type (home, work, school, gym, other)',
                'example'     => 'work',
            ],
        ];
    }

    public function toData(): FavoriteRouteData
    {
        /** @var array{name?: string, origin_address?: string, origin_lat?: float, origin_lng?: float, destination_address?: string, destination_lat?: float, destination_lng?: float, type?: string|null} $validated */
        $validated = $this->validated();

        return FavoriteRouteData::fromArray([
            'name'                => $validated['name'] ?? '',
            'origin_address'      => $validated['origin_address'] ?? '',
            'origin_lat'          => $validated['origin_lat'] ?? 0.0,
            'origin_lng'          => $validated['origin_lng'] ?? 0.0,
            'destination_address' => $validated['destination_address'] ?? '',
            'destination_lat'     => $validated['destination_lat'] ?? 0.0,
            'destination_lng'     => $validated['destination_lng'] ?? 0.0,
            'type'                => $validated['type'] ?? null,
        ]);
    }
}
